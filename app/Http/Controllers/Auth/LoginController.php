<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Validator;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect user after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        $this->middleware('guest', ['except' => 'logout']);
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
        if ($validator->fails()) {
            return redirect('/login')->withInput()->withErrors($validator);
        } else {
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
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
