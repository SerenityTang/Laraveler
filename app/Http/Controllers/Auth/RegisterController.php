<?php

namespace App\Http\Controllers\Auth;

use App\Models\User_data;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Validator;

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
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $input = $request->only(['username', 'password', 'password_confirmation','email','mobile','m_code']);
        $rules = array(
            'username' => 'required|string|max:255|unique:user',
            'password' => 'required|string|between:6,20|confirmed',
            'password_confirmation' => 'required|string',
            'email' => 'required|string|email|max:255|unique:user',
            'mobile' => 'required|string|min:11|regex:/^1[34578][0-9]{9}$/|unique:user',
            //'m_code' => 'required|string'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect('/register')->withInput()->withErrors($validator);
        } else {
            $user = $this->create($input);
            return $this->success(route('login'), '亲爱的' . $user->username . '，恭喜您注册成功，请登录...');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user_data = [
            'username' => $data['username'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => bcrypt($data['password']),
            'user_status' => 1,
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
}
