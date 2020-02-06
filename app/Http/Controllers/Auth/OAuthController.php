<?php

namespace App\Http\Controllers\Auth;

use App\Models\UserData;
use App\Models\UserSocialite;
use App\Services\Ucpaas\Agents\UcpaasAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserOauth;
use App\Models\User;
use App\Models\UserProfile;
use BrowserDetect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Validator;

class OAuthController extends Controller
{
    /**
     * 第三方授权认证用户绑定处理表单ajax验证
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindVerify(Request $request)
    {
        $data = $request->all();
        if (isset($data['username'])) {
            $rules = [
                'username' => 'required|string|between:2,15',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else {
                $checkUsername = User::where('username', $data['username'])->first();
                if ($checkUsername) return $this->jsonResult(906);
                return $this->jsonResult(907);
            }
        } elseif (isset($data['mobile'])) {
            $rules = [
                'mobile' => 'required|bail|string|min:11|regex:/^1[34578][0-9]{9}$/',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else {
                $checkMobile = User::where('mobile', $data['mobile'])->first();
                if ($checkMobile) return $this->jsonResult(902);
                return $this->jsonResult(903);
            }
        } elseif (isset($data['password'])) {
            $rules = [
                'password' => 'required|string|between:6,20',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else {
                return $this->jsonResult(904);
            }
        }
    }

    /**
     * callback获取手机验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMobileCode(Request $request)
    {
        $data = $request->all();
        if ($request->input('pwd_status') == 0) {
            $rules = array(
                'username' => 'required|string|between:2,15',
                'mobile' => 'required|bail|string|min:11|regex:/^1[34578][0-9]{9}$/',
                'password' => 'required|string|between:6,20',
            );
        } else if ($request->input('pwd_status') == 1) {
            $rules = array(
                'username' => 'required|string|between:2,15',
                'mobile' => 'required|bail|string|min:11|regex:/^1[34578][0-9]{9}$/',
            );
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $this->jsonResult(502, $validator->errors());
        } else {
            $user = User::where('username', $data['username'])->first();
            if ($user) {
                //获取验证码前判断手机号不存在且用户名存在
                return $this->jsonResult(906);
            } else {
                return $this->send($data['mobile']);
            }
        }
    }

    /**
     * 发送手机验证码
     *
     * @param $mobile
     * @return \Illuminate\Http\JsonResponse
     */
    public function send($mobile)
    {
        /**
         * 验证成功，发送验证码
         * 短信接口请求参数
         **/
        $appid = env('AppID');
        $templateid = env('Template_Id_Register');
        $options['accountsid'] = env('Account_Sid');
        $options['token'] = env('Auth_Token');
        $ucpass = new UcpaasAgent($options);

        $verifyCode = phoneCode();
        $param = "$verifyCode,5";

        //发送短信前先删除此用户的短信验证码缓存
        if (Cache::has($mobile . 'minute')) {
            return $this->jsonResult(899);
        } else {
            Cache::forget($mobile);
            Cache::forget($mobile . 'minute');
        }

        //发送短信验证码
        $data = $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid = null);
        //json格式的字符串进行解码，返回对象变量，如第二个参数true，返回数组 | json_encode()对变量进行 JSON 编码
        $backData = json_decode($data, true);

        if ($backData['code'] == '000000') {
            //发送成功，把短信验证码保存在缓存 key：手机号，value：验证码随机数
            Cache::put($mobile, $verifyCode, 5);     //短信验证码
            Cache::put($mobile . 'minute', 1, 1);    //记录此手机一分钟内获取验证码标记

            return $this->jsonResult(900);
        } else {
            //发送失败
            return $this->jsonResult(901);
        }
    }

    /**
     * callback->bind 第三方授权认证登录后的本地平台用户绑定处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bind(Request $request)
    {
        $username = $request->input('username');
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        //$verify_code = $request->input('verify_code');
        $driver = $request->input('driver');

        //验证手机验证码
        $rules = [
            'verify_code' => 'required|string|validateMobile:' . $mobile,
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->jsonResult(502, $validator->errors());
        } else {
            if (Auth::check()) {
                /*// 已登录，直接绑定
                $openid = $request->input('oauth_id');
                $driver = $request->input('oauth_type');

                // 判断是否注册过
                $user = User::getByDriver($driver, $openid);

                if ($user != null) {
                    // 如果存在，直接跳转

                    // 更新已存在的关联信息
                    UserOauth::where('oauth_type', $driver)->where('oauth_id', $openid)->update(['nickname' => $request->input('name'), 'avatar' => $request->input('avatar')]);

                    return redirect($redirect_uri ? $redirect_uri : 'setting');
                } else {
                    //否则，绑定老账号

                    //写入 OAuth 数据
                    $this->oauthSave($request, Auth::user()->id);

                    //写入 User Profile 数据
                    $profile = UserProfile::where('uid', Auth::user()->id)->first();//查看是否存在profile记录，如果存在，不做写入操作。
                    if ($profile == null) {
                        $this->profileSave($request, Auth::user()->id);
                    }

                    return redirect($redirect_uri ? $redirect_uri : 'setting');
                }*/
            } else {
                if ($password) {
                    //密码不为空则新建账户
                    $UserData = [
                        'username' => $username,
                        'mobile' => $mobile,
                        'password' => bcrypt($password),
                        'user_status' => 1,
                        'personal_domain' => $username,
                    ];
                    $user = User::create($UserData);

                    if ($user) {
                        // 用户信息子表
                        $data = [
                            'user_id' => $user->id,
                        ];
                        $UserData = UserData::create($data);
                        if ($UserData) {
                            // 更新第三方信息表用户ID字段
                            $this->socialiteSave($request, $user->id);
                            // 更新用户表(第三方信息覆盖)
                            $this->unRegisterUserSave($request, $user->id, $driver);
                            // 登录
                            Auth::login($user);
                            //return $this->success('/', '亲爱的' . $user->username . '，恭喜您成功注册并绑定了 '. $driver .' 社交账号 ^_^');
                            return $this->jsonResult(501, '亲爱的' . $user->username . '，恭喜您成功注册并绑定了 ' . $driver . ' 社交账号 ^_^');
                        }
                    } else {
                        //return $this->error('/', '用户注册失败');
                        return $this->jsonResult(911);
                    }
                } else {
                    //密码为空则为此账户绑定第三方账号
                    //if (Auth::attempt(['mobile' => $mobile])) {
                    $user = User::where('mobile', $mobile)->first();

                    if ($user) {
                        $UserData = UserData::where('user_id', $user->id)->first();
                        // 更新第三方信息表用户ID字段
                        $this->socialiteSave($request, $user->id);
                        // 用户信息子表(确保子表有信息)
                        if (!$UserData) {
                            UserData::create(['user_id' => $user->id]);
                        }
                        // 更新用户表(第三方信息覆盖)
                        $this->registerUserSave($request, $user->id, $driver);
                        // 登录
                        Auth::login($user);
                        //return $this->success('/', '亲爱的' . $user->username . '，恭喜您成功绑定了社交账号 ^_^');
                        return $this->jsonResult(501, '亲爱的' . $user->username . '，恭喜您成功绑定了社交账号 ^_^');
                    } else {
                        //return $this->error('/', '用户不存在');
                        return $this->jsonResult(912);
                    }

                    //}
                }
            }
        }
    }

    /**
     * 写入 OAuth 数据
     *
     * @param Request $request
     * @param int $user_id 用户id
     */
    private function socialiteSave(Request $request, $user_id)
    {
        $UserSocialite = UserSocialite::firstOrNew(['oauth_type' => $request->get('oauth_type'), 'oauth_id' => $request->get('oauth_id')]);
        $UserSocialite->user_id = $user_id;
        $UserSocialite->save();
    }

    /**
     * 写入 User 数据（用户未注册）
     *
     * @param Request $request
     * @param int $user_id 用户id
     */
    private function unRegisterUserSave(Request $request, $user_id, $driver)
    {
        $user = User::where('id', $user_id)->first();
        switch ($driver) {
            case 'weibo':
                $user->realname = $request->get('realname');
                $user->email = $request->get('email');
                //$user->avatar = $request->get('avatar');
                $user->gender = $request->get('gender');
                $user->weibo_name = $request->get('nickname');
                $user->weibo_link = $request->get('weibo');
                $user->province = $request->get('province');
                $user->city = $request->get('city');
                $user->save();

                break;
            case 'github':
                $user->realname = $request->get('realname');
                $user->email = $request->get('email');
                //$user->avatar = $request->get('avatar');
                $user->gender = $request->get('gender');
                $user->github_name = $request->get('nickname');
                $user->github_link = $request->get('github');
                $user->province = $request->get('province');
                $user->city = $request->get('city');
                $user->save();

                break;
            case 'qq':
                $user->realname = $request->get('realname');
                $user->email = $request->get('email');
                //$user->avatar = $request->get('avatar');
                $user->gender = $request->get('gender');
                $user->qq_name = $request->get('nickname');
                //$user->qq = $request->get('qq');
                $user->province = $request->get('province');
                $user->city = $request->get('city');
                $user->save();

                break;
        }
    }

    /**
     * 写入 User 数据（用户已注册）
     * @param Request $request
     * @param int $user_id 用户id
     */
    private function registerUserSave(Request $request, $user_id, $driver)
    {
        $user = User::where('id', $user_id)->first();
        switch ($driver) {
            case 'weibo':
                $user->realname = is_null($user->realname) ? $request->get('realname') : $user->realname;
                $user->email = is_null($user->email) ? $request->get('email') : $user->email;
                //$user->avatar = is_null($user->avatar) ? $request->get('avatar') : $user->avatar;
                $user->gender = is_null($user->gender) ? $request->get('gender') : $user->gender;
                $user->weibo_name = is_null($user->weibo_name) ? $request->get('nickname') : $user->weibo_name;
                $user->weibo_link = is_null($user->weibo_link) ? $request->get('weibo') : $user->weibo_link;
                $user->province = is_null($user->province) ? $request->get('province') : $user->province;
                $user->city = is_null($user->city) ? $request->get('city') : $user->city;
                $user->save();

                break;
            case 'github':
                $user->realname = is_null($user->realname) ? $request->get('realname') : $user->realname;
                $user->email = is_null($user->email) ? $request->get('email') : $user->email;
                //$user->avatar = is_null($user->avatar) ? $request->get('avatar') : $user->avatar;
                $user->gender = is_null($user->gender) ? $request->get('gender') : $user->gender;
                $user->github_name = is_null($user->github_name) ? $request->get('nickname') : $user->github_name;
                $user->github_link = is_null($user->github_link) ? $request->get('github') : $user->github_link;
                $user->province = is_null($user->province) ? $request->get('province') : $user->province;
                $user->city = is_null($user->city) ? $request->get('city') : $user->city;
                $user->save();

                break;
            case 'qq':
                $user->realname = is_null($user->realname) ? $request->get('realname') : $user->realname;
                $user->email = is_null($user->email) ? $request->get('email') : $user->email;
                //$user->avatar = is_null($user->avatar) ? $request->get('avatar') : $user->avatar;
                $user->gender = is_null($user->gender) ? $request->get('gender') : $user->gender;
                $user->qq_name = is_null($user->qq_name) ? $request->get('nickname') : $user->qq_name;
                //$user->qq = is_null($user->qq) ? $request->get('qq') : $user->qq;
                $user->province = is_null($user->province) ? $request->get('province') : $user->province;
                $user->city = is_null($user->city) ? $request->get('city') : $user->city;
                $user->save();

                break;
        }
    }
}
