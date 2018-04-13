<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SocialiteHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating user for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, SocialiteHelper;

    /**
     * Where to redirect user after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        $this->middleware('guest', ['except' => ['logout', 'oauth', 'callback']]);
    }

    /**
     * 重写登录方法，把验证规则改写一并放里面
     *
     * @return void
     */
    public function login(Request $request) {
        $input = $request->only(['username', 'password', 'captcha']);
        $rules = [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|validateCaptcha',
        ];
        //Validator::make($input, $rules, $messages, $attributes)->validate();  //如验证失败，将被自动重定向或者返回json响应给上一个位置

        $validator = Validator::make($input, $rules);
        //判断是否存在错误信息
        if ($validator->fails()) {
            return redirect('/login')->withInput()->withErrors($validator);
        } else {

            $credentials = $this->credentials($request);    //从请求获取登录需要的字段
            $credentials['user_status'] = 1;    //添加条件用户状态为 1

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            /*if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }*/
            if ($this->guard()->attempt($credentials, $request->has('remember'))) {
                return $this->sendLoginResponse($request);      //登录成功，触发自带的登录监听，记录登录时间，返回成功登录；登出也类似触发登出监听
            } else {
                return $this->error('/login', '抱歉，您的帐号被禁用无法登录，请联系管理员...');
            }

            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        /*$account = request()->input('username');
        if ($account) {
            return Helpers::username($account);
        }*/
        return 'username';
    }
}
