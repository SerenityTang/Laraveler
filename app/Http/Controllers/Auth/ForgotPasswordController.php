<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Ucpaas\Agents\UcpaasAgent;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('pc.auth.passwords.email');
    }

    /**
     * 获取手机验证码前验证与验证码发送
     *
     */
    public function mobile_verify_code(Request $request) {
        $input = $request->only(['username', 'captcha']);
        $mobile = User::where('mobile', $input['username'])->first();
        $rules = [
            'username'      =>'required',
            'captcha'       =>'required|validateCaptcha',
        ];

        $validator = Validator::make($input, $rules);
        if (!$mobile) {
            return $this->jsonResult(895);
        }else if ($validator->fails()) {
            return $this->jsonResult(502, $validator->errors());
        } else {
            //短信接口请求参数
            $appid = env('AppID');
            $templateid = env('Template_Id_Forget');

            $options['accountsid'] = env('Account_Sid');
            $options['token'] = env('Auth_Token');
            $ucpass = new UcpaasAgent($options);

            $verify_code = '';
            for ($i = 0; $i < 6; $i++) {
                $verify_code .= random_int(0, 9);
            }

            $param = "$verify_code,5";

            //发送短信前先删除此用户的短信验证码缓存
            if (Cache::has($input['username'].'minute')) {
                return $this->jsonResult(899);
            } else {
                Cache::forget($input['username']);
                Cache::forget($input['username'].'minute');
            }

            //发送短信验证码
            $data = $ucpass->SendSms($appid, $templateid, $param, $input['username'], $uid = null);
            //json格式的字符串进行解码，返回对象变量，如第二个参数true，返回数组 | json_encode()对变量进行 JSON 编码
            $back_data = json_decode($data, true);

            if ($back_data['code'] == '000000') {
                //发送成功，把短信验证码保存在缓存 key：手机号，value：验证码随机数
                Cache::put($request->input('mobile'), $verify_code, 5);     //短信验证码
                Cache::put($request->input('mobile').'minute', 1, 1);       //记录此手机一分钟内获取验证码标记

                return $this->jsonResult(900);
            } else {
                //发送失败
                return $this->jsonResult(901);
            }
        }
    }

    /**
     * 验证手机验证码
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function forgot_submit(Request $request)
    {
        $input = $request->only(['username', 'm_code']);
        $rules = [
            'm_code' => 'required|validateMobile:'.$input['username'],
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return $this->jsonResult(502, $validator->errors());
        } else {
            return $this->jsonResult(501);
        }
    }

    /**
     * 提交新密码
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function reset_submit(Request $request)
    {
        $input = $request->only(['username', 'password']);
        $rules = [
            'password' => 'required|string|between:6,20',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return $this->jsonResult(502, $validator->errors());
        } else {
            $user = User::where('mobile', $input['username'])->first();
            $user->password = bcrypt($input['password']);
            $bool = $user->save();
            if ($bool == true) {
                return $this->jsonResult(913);
            }
        }
    }
}
