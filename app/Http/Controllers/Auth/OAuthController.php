<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserOauth;
use App\Models\User;
use App\Models\UserProfile;
use Auth;
use \BrowserDetect;

class OAuthController extends Controller
{
    /**
     * callback->bind 第三方授权认证登录后的本地平台用户绑定处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bind(Request $request)
    {
        $redirect_uri = $request->input('redirect_uri');

        if (Auth::check()) {
            // 已登录，直接绑定
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
            }
        } else {
            $errors = [];

            $mobile = $request->input('mobile');
            $password = $request->get('password');
            if ($mobile || $password) {
                if (!$mobile) {
                    $errors = ['mobile' => '手机号不能为空'];
                } else if (!$password) {
                    $errors = ['password' => '密码不能为空'];
                } else {
                    $field = filter_var($mobile, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
                    if (User::where($field, $mobile)->first()) {
                        // 账号存在，验证登录授权
                        if (Auth::attempt([$field => $mobile, 'password' => $password])) {
                            // 登入成功

                            // 写入 OAuth 数据
                            $this->oauthSave($request, Auth::user()->id);

                            // 写入 User Profile 数据
                            $profile = UserProfile::where('uid', Auth::user()->id)->first();//查看是否存在profile记录，如果存在，不做写入操作。
                            if ($profile == null) {
                                $this->profileSave($request, Auth::user()->id);
                            }

                            return redirect($redirect_uri ? $redirect_uri : 'setting');
                        } else {
                            // 登入失败

                            $errors = ['mobile' => '手机号或密码不正确'];
                        }
                    } else {
                        // 账号不存在，需要注册并且登录！！！

                        // 注册用户
                        $homepage = \App\Helpers\Helpers::generateNumber();
                        $user = User::create([
                            'name'     => $mobile,
                            $field     => $mobile,
                            'homepage' => $homepage,
                            'password' => bcrypt($password),
                        ]);

                        // 写入 OAuth 数据
                        $this->oauthSave($request, $user->id);

                        // 写入 User Profile 数据
                        $this->profileSave($request, $user->id);

                        if (Auth::attempt([$field => $mobile, 'password' => $password])) {
                            return redirect($redirect_uri ? $redirect_uri : 'setting');
                        }

                        return redirect($redirect_uri ? $redirect_uri : '/');
                    }
                }
            }
            $profile = (object)$request->except('redirect_uri');
            // 判断是否手机
            if (BrowserDetect::isMobile()) {
                return view('mobile.auth.bind')
                    ->with(['profile' => $profile, 'redirect_uri' => $redirect_uri])
                    ->withErrors($errors);
            } else {
                return view('pc.auth.bind')
                    ->with(['profile' => $profile, 'redirect_uri' => $redirect_uri])
                    ->withErrors($errors);
            }
        }
    }

    /**
     * 写入 OAuth 数据
     * @param Request $request
     * @param int     $user_id 用户id
     */
    private function oauthSave(Request $request, $user_id)
    {
        $oauth = UserOauth::firstOrNew(['oauth_type' => $request->get('oauth_type'), 'oauth_id' => $request->get('oauth_id')]);
        $oauth->uid = $user_id;
        $oauth->save();
    }

    /**
     * 写入 User Profile 数据
     * @param Request $request
     * @param int     $user_id 用户id
     */
    private function profileSave(Request $request, $user_id)
    {
        $profile = new UserProfile();
        $profile->uid = $user_id;
        $profile->realname = $request->get('realname');
        $profile->avatar = $request->get('avatar');
        $profile->cover = 'cover/default.jpg';
        $profile->music = 1;
        $profile->notify = 1;
        $profile->gender = $request->get('gender');
        $profile->weibo = $request->get('weibo');
        $profile->province = $request->get('province');
        $profile->city = $request->get('city');
        $profile->save();
    }
}
