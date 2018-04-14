<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/4/7
 * Time: 下午10:31
 */

namespace App\Http\Controllers\Traits;

use App\Models\User_socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Flash;
use Laravel\Socialite\Facades\Socialite;

trait SocialiteHelper
{
    protected $oauthDrivers = ['qq' => 'qq', 'weibo' => 'weibo', 'weixin' => 'weixin', 'github' => 'github'];

    public function oauth($driver)
    {
        //return view('auth.oauth.callback');
        $driver = !isset($this->oauthDrivers[$driver]) ? $this->oauthDrivers['weixin'] : $this->oauthDrivers[$driver];

        if (Auth::check()) {
            $oauth = User_socialite::where('oauth_type', $driver)->where('user_id', Auth::user()->id)->first();
            if ($oauth && $oauth->oauth_type == $driver) {
                return redirect('/');
            } else {
                return Socialite::driver($driver)->redirect();
            }
        } else {
            return Socialite::driver($driver)->redirect();
        }
    }

    public function callback($driver)
    {
        if (Auth::check()) {
            $oauth = User_socialite::where('oauth_type', $driver)->where('user_id', Auth::user()->id)->first();
            if (!isset($this->oauthDrivers[$driver]) || ($oauth && $oauth->oauth_type == $driver)) {
                return redirect()->intended('/');
            }
        }
        if ($this->oauthDrivers[$driver] == 'github') {
            $oauthUser = Socialite::driver('github')->user();
        } else {
            $oauthUser = Socialite::with($this->oauthDrivers[$driver])->user();
        }

        $userSocialite = User_socialite::where('oauth_type', $driver)->where('oauth_id', $oauthUser->id)->first();
        if (!$userSocialite) {
            $userSocialite = User_socialite::create(['oauth_type' => $driver, 'oauth_id' => $oauthUser->id]);
        }
        $userSocialite->oauth_type = $driver;
        $userSocialite->oauth_id = $oauthUser->id;
        $userSocialite->oauth_access_token = $oauthUser->token;
        $userSocialite->oauth_expires = $oauthUser->expiresIn;
        if ($oauthUser->nickname) {
            $userSocialite->nickname = $oauthUser->nickname;
            $userSocialite->avatar = $oauthUser->avatar;
        }
        $userSocialite->save();

        $user = User::getByDriver($driver, $oauthUser->id);
        if ($user) {
            // 如注册过，说明用户绑定第三方账号，直接登录并跳转
            if (!Auth::check()) {
                Auth::login($user);
            }

            return redirect('/');
        } else {
            // 注册新账号，或者绑定老账号
            $profile = $this->bindSocialiteUserByGuest($oauthUser, $driver);

            return view('auth.oauth.callback')->with(['profile' => $profile, 'driver' => $driver]);
        }
    }

    /**
     * 登录第三方账号->绑定用户
     * @param object $oauthUser 第三方账号对象
     * @param string $driver    认证方式
     * @return object
     */
    public function bindSocialiteUserByGuest($oauthUser, $driver)
    {
        $profile = (object)array();
        switch ($driver) {
            case 'weixin':
                $profile->oauth_type = 'weixin';
                $profile->oauth_id = $oauthUser->id;
                $profile->oauth_access_token = $oauthUser->token;
                $profile->oauth_expires = $oauthUser->expiresIn;
                $profile->avatar = $oauthUser->avatar;
                $profile->nickname = $oauthUser->nickname;
                $profile->realname = $oauthUser->name;
                $profile->gender = $oauthUser->user['sex'];
                $profile->email = $oauthUser->email;
                $profile->province = $oauthUser->user['province'];
                $profile->city = $oauthUser->user['city'];
                $profile->weibo = '';
                $profile->qq = '';
                $profile->github = '';
                break;
            case 'weixinweb':
                $profile->oauth_type = 'weixinweb';
                $profile->oauth_id = $oauthUser->id;
                $profile->oauth_access_token = $oauthUser->token;
                $profile->oauth_expires = $oauthUser->expiresIn;
                $profile->avatar = $oauthUser->avatar;
                $profile->nickname = $oauthUser->nickname;
                $profile->realname = $oauthUser->name;
                $profile->gender = $oauthUser->user['sex'];
                $profile->email = $oauthUser->email;
                $profile->province = $oauthUser->user['province'];
                $profile->city = $oauthUser->user['city'];
                $profile->weibo = '';
                $profile->github = '';
                break;
            case 'qq':
                $profile->oauth_type = 'qq';
                $profile->oauth_id = $oauthUser->id;
                $profile->oauth_access_token = $oauthUser->token;
                $profile->oauth_expires = $oauthUser->expiresIn;
                $profile->avatar = $oauthUser->avatar;
                $profile->nickname = $oauthUser->nickname;
                $profile->realname = $oauthUser->name;
                $profile->gender = $oauthUser->gender = '男' ? 0 : 1;
                $profile->email = $oauthUser->email;
                $profile->province = $oauthUser->user['province'];
                $profile->city = $oauthUser->user['city'];
                $profile->weibo = '';
                $profile->github = '';
                break;
            case 'weibo':
                $profile->oauth_type = 'weibo';
                $profile->oauth_id = $oauthUser->id;
                $profile->oauth_access_token = $oauthUser->token;
                $profile->oauth_expires = $oauthUser->expiresIn;
                $profile->avatar = $oauthUser->avatar;
                $profile->nickname = $oauthUser->nickname;
                $profile->realname = $oauthUser->user['name'];
                $profile->gender = $oauthUser->user['gender'] = 'm' ? 0 : 1;
                $profile->email = $oauthUser->email;
                $location = explode(" ", $oauthUser->user['location']);
                $province = '';
                $city = '';
                if (isset($location[0])) {
                    $province = $location[0];
                }
                if (isset($location[1])) {
                    $city = $location[1];
                }
                $profile->province = $province;
                $profile->city = $city;
                $profile->weibo = 'http://weibo.com/' . $oauthUser->user['profile_url'];
                $profile->github = '';
                break;
            case 'github':
                $profile->oauth_type = 'github';
                $profile->oauth_id = $oauthUser->id;
                $profile->oauth_access_token = $oauthUser->token;
                $profile->oauth_expires = $oauthUser->expiresIn;
                $profile->avatar = $oauthUser->avatar;
                $profile->nickname = $oauthUser->nickname;
                $profile->realname = $oauthUser->name;
                $profile->gender = '';
                $profile->email = $oauthUser->email;
                $profile->province = '';
                $profile->city = '';
                $profile->weibo = '';
                $profile->github = $oauthUser->user['html_url'];
                break;
            default:
                $profile->oauth_type = 'weixin';
                $profile->oauth_id = $oauthUser->id;
                $profile->oauth_access_token = $oauthUser->token;
                $profile->oauth_expires = $oauthUser->expiresIn;
                $profile->avatar = $oauthUser->avatar;
                $profile->nickname = $oauthUser->nickname;
                $profile->realname = $oauthUser->name;
                $profile->gender = $oauthUser->user['sex'];
                $profile->email = $oauthUser->email;
                $profile->province = $oauthUser->user['province'];
                $profile->city = $oauthUser->user['city'];
                $profile->weibo = '';
                $profile->github = '';
        }

        return $profile;
    }
}