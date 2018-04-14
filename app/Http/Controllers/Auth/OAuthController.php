<?php

namespace App\Http\Controllers\Auth;

use App\Models\User_data;
use App\Models\User_socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserOauth;
use App\User;
use App\Models\UserProfile;
use BrowserDetect;
use Illuminate\Support\Facades\Auth;
use Validator;

class OAuthController extends Controller
{
    /**
     * 第三方授权认证登录后的本地平台用户绑定处理表单ajax验证
     */
    public function bind_verify(Request $request)
    {
        $input = $request->only(['username', 'password', 'mobile','captcha']);
        if (isset($input['username']) && $input['username'] != null) {
            $username = User::where('username', $input['username'])->first();
            if ($username) {
                return $this->jsonResult(906);
            } else {
                return $this->jsonResult(907);
            }
        } else if (isset($input['mobile']) && $input['mobile'] != null) {
            $rules = array(
                'mobile' => 'bail|string|min:11|regex:/^1[34578][0-9]{9}$/',
            );
            $validator = Validator::make($input, $rules);
            $mobile_user = User::where('mobile', $input['mobile'])->first();
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else if ($mobile_user) {
                return $this->jsonResult(902);
            } else {
                return $this->jsonResult(903);
            }
        } else if (isset($input['password']) && $input['password'] != null) {
            $rules = array(
                'password' => 'string|between:6,20',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else {
                return $this->jsonResult(904);
            }
        } else if (isset($input['captcha']) && $input['captcha'] != null) {
            $rules = array(
                'captcha' => 'required|validateCaptcha',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else {
                return $this->jsonResult(905);
            }
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
        $driver = $request->input('driver');
        $rules = array(
            'username' => 'required|string|max:255|unique:user',
            'mobile' => 'required|string|min:11|regex:/^1[34578][0-9]{9}$/',
            'password' => 'required|string|between:6,20',
            'captcha' => 'required|string|validateCaptcha',
        );

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
                    $user_data = [
                        'username' => $username,
                        'mobile' => $mobile,
                        'password' => bcrypt($password),
                        'user_status' => 1,
                        'personal_domain' => $username,
                    ];
                    $user = User::create($user_data);

                    if ($user) {
                        $data = [
                            'user_id'       => $user->id,
                        ];
                        $user_data = User_data::create($data);
                        if ($user_data) {
                            $this->socialiteSave($request, $user->id);

                            $this->unRegisterUserSave($request, $user->id, $driver);

                            Auth::login($user);
                            return $this->success(route('home'), '亲爱的' . $user->username . '，恭喜您成功注册并绑定了 '. $driver .' 社交账号 ^_^');
                        }
                    } else {
                        return $this->error(route('home'), '用户注册失败');
                    }
                } else {
                    //密码为空则为此账户绑定第三方账号
                    //if (Auth::attempt(['mobile' => $mobile])) {
                    $user = User::where('mobile', $mobile)->first();
                    $user_data = User_data::where('user_id', $user->id)->first();

                    if ($user) {
                        $this->socialiteSave($request, $user->id);

                        if (!$user_data) {
                            User_data::create(['user_id' => $user->id]);
                        }

                        $this->registerUserSave($request, $user->id, $driver);

                        Auth::login($user);
                        return $this->success(route('home'), '亲爱的' . $user->username . '，恭喜您成功绑定了社交账号 ^_^');
                    } else {
                        return $this->error(route('home'), '用户不存在');
                    }

                    //}
                }
            }
        }
    }

    /**
     * 写入 OAuth 数据
     * @param Request $request
     * @param int     $user_id 用户id
     */
    private function socialiteSave(Request $request, $user_id)
    {
        $user_socialite = User_socialite::firstOrNew(['oauth_type' => $request->get('oauth_type'), 'oauth_id' => $request->get('oauth_id')]);
        $user_socialite->user_id = $user_id;
        $user_socialite->save();
    }

    /**
     * 写入 User 数据（用户未注册）
     * @param Request $request
     * @param int     $user_id 用户id
     */
    private function unRegisterUserSave(Request $request, $user_id, $driver)
    {
        $user = User::where('id', $user_id)->first();
        switch ($driver) {
            case 'weibo':
                $user->realname = $request->get('realname');
                $user->email = $request->get('email');
                $user->avatar = $request->get('avatar');
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
                $user->avatar = $request->get('avatar');
                $user->gender = $request->get('gender');
                $user->github_name = $request->get('nickname');
                $user->github_link = $request->get('github');
                $user->province = $request->get('province');
                $user->city = $request->get('city');
                $user->save();

                break;
        }
    }

    /**
     * 写入 User 数据（用户已注册）
     * @param Request $request
     * @param int     $user_id 用户id
     */
    private function registerUserSave(Request $request, $user_id, $driver)
    {
        $user = User::where('id', $user_id)->first();
        switch ($driver) {
            case 'weibo':
                $user->realname = is_null($user->realname) ? $request->get('realname') : $user->realname;
                $user->email = is_null($user->email) ? $request->get('email') : $user->email;
                $user->avatar = is_null($user->avatar) ? $request->get('avatar') : $user->avatar;
                $user->gender = is_null($user->gender) ? $request->get('gender') : $user->gender;
                $user->weibo = is_null($user->nickname) ? $request->get('nickname') : $user->nickname;
                $user->weibo_link = is_null($user->weibo) ? $request->get('weibo') : $user->weibo;
                $user->province = is_null($user->province) ? $request->get('province') : $user->province;
                $user->city = is_null($user->city) ? $request->get('city') : $user->city;
                $user->save();

                break;
            case 'github':
                $user->realname = is_null($user->realname) ? $request->get('realname') : $user->realname;
                $user->email = is_null($user->email) ? $request->get('email') : $user->email;
                $user->avatar = is_null($user->avatar) ? $request->get('avatar') : $user->avatar;
                $user->gender = is_null($user->gender) ? $request->get('gender') : $user->gender;
                $user->weibo = is_null($user->nickname) ? $request->get('nickname') : $user->nickname;
                $user->weibo_link = is_null($user->weibo) ? $request->get('weibo') : $user->weibo;
                $user->github_link = is_null($user->github) ? $request->get('github') : $user->github;
                $user->province = is_null($user->province) ? $request->get('province') : $user->province;
                $user->city = is_null($user->city) ? $request->get('city') : $user->city;
                $user->save();

                break;
        }
    }
}
