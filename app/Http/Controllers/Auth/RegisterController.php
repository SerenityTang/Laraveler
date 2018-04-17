<?php

namespace App\Http\Controllers\Auth;

use App\Models\User_data;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Ucpaas\Agents\UcpaasAgent;
use Validator;
use SmsManager;
use Cache;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new user as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect user after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/';

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        SmsManager::forgetState();
        return view('auth.register');
    }

    /**
     * 注册
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $input = $request->only(['username', 'password', 'password_confirmation',/*'email',*/'mobile','m_code']);
        //验证表单
        $validator = $this->validator($input);
        //判断是否存在错误信息
        if ($validator->fails()) {
            return redirect('/register')->withInput()->withErrors($validator);
        } else {
            //验证通过创建新用户
            $user = $this->create($input);
            //创建后自动登录
            Auth::login($user);
            //注册成功，删除此用户的短信验证码缓存
            Cache::forget($input['mobile']);
            Cache::forget($input['mobile'].'minute');

            return $this->success(route('home'), '亲爱的 ' . $user->username . '，恭喜您注册成功 ^_^');
        }
    }

    /**
     * 注册表单验证
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = array(
            'username' => 'required|string|max:255|unique:user',
            'password' => 'required|string|between:6,20|confirmed',
            'password_confirmation' => 'required|string',
            'mobile' => 'required|string|min:11|regex:/^1[34578][0-9]{9}$/|unique:user',
            'm_code' => 'required|validateMobile:'.$data['mobile'],
        );

        $validator = Validator::make($data, $rules);
        return $validator;
    }

    /**
     * 注册创建新用户
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user_data = [
            'username' => $data['username'],
            'mobile' => $data['mobile'],
            'password' => bcrypt($data['password']),
            'user_status' => 1,
            'personal_domain' => $data['username'],
        ];
        $user = User::create($user_data);

        if ($user) {
            $data = [
                'user_id'       => $user->id,
            ];
            User_data::create($data);
        }
        return $user;
    }

    /**
     * 短信验证码验证
     *
     * @param  Request $request
     * @return \App\User
     */
    protected function note_verify_code(Request $request)
    {
        //验证数据
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:user',
            'password' => 'required|string|between:6,20|confirmed',
            'password_confirmation' => 'required|string',
            'mobile'     => 'required|string|min:11|regex:/^1[34578][0-9]{9}$/|unique:user',
        ]);
        if ($validator->fails()) {
            //验证失败后建议清空存储的发送状态，防止用户重复试错
            SmsManager::forgetState();
            return $this->jsonResult(502, $validator->errors());
        }

        //短信接口请求参数
        $appid = env('AppID');
        $templateid = env('Template_Id_Register');

        $options['accountsid'] = env('Account_Sid');
        $options['token'] = env('Auth_Token');
        $ucpass = new UcpaasAgent($options);

        //获取手机号
        $mobile = $request->get('mobile');

        $verify_code = '';
        for ($i = 0; $i < 6; $i++) {
            $verify_code .= random_int(0, 9);
        }

        $param = "$verify_code,5";

        //发送短信前先删除此用户的短信验证码缓存
        if (Cache::has($mobile.'minute')) {
            return $this->jsonResult(899);
        } else {
            Cache::forget($mobile);
            Cache::forget($mobile.'minute');
        }

        //发送短信验证码
        $data = $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid = null);
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
