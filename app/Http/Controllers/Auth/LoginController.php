<?php

namespace App\Http\Controllers\Auth;

use App\Events\WelcomeEvent;
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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('pc.auth.login');
    }

    public function attemptLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // 验证用户名登录方式
        $usernameLogin = $this->guard()->attempt(
            ['username' => $username, 'password' => $password, 'user_status' => 1], $request->has('remember')
        );
        if ($usernameLogin) {
            return true;
        }

        // 验证手机号登录方式
        $mobileLogin = $this->guard()->attempt(
            ['mobile' => $username, 'password' => $password, 'user_status' => 1], $request->has('remember')
        );
        if ($mobileLogin) {
            return true;
        }

        // 验证邮箱登录方式
        $emailLogin = $this->guard()->attempt(
            ['email' => $username, 'password' => $password, 'user_status' => 1], $request->has('remember')
        );
        if ($emailLogin) {
            return true;
        }

        return false;
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

            /*$credentials = $this->credentials($request);    //从请求获取登录需要的字段
            $credentials['user_status'] = 1;    //添加条件用户状态为 1*/

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            /*if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }*/
            if ($this->attemptLogin($request) == true) {
                event(new WelcomeEvent($input['username']));

                return $this->sendLoginResponse($request);      //登录成功，触发自带的登录监听，记录登录时间，返回成功登录；登出也类似触发登出监听
            } else {
                return $this->error('/login', '抱歉，您输入用户名&密码错误或帐号被禁用，请检查登录信息或联系管理员...');
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
        return 'username';
    }
}
