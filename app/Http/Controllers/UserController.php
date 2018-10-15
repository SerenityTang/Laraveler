<?php

namespace App\Http\Controllers;

use App\Events\HomepageViewEvent;
use App\Models\Attention;
use App\Models\Blog;
use App\Models\CareerDirection;
use App\Models\PersonalDynamic;
use App\Models\Question;
use App\Models\UserData;
use App\Models\UserActivation;
use App\Models\UserAuthenticate;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use App\Models\UserSocialite;
use App\Services\Qiniu\QiNiuCloud;
use App\Services\Ucpaas\Agents\UcpaasAgent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use SmsManager;
use Cache;

//use App\Services\OSS;

class UserController extends Controller
{
    /**
     * 用户个人主页
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;

        //统计主页被访问记录
        $curr_user = Auth::check() ? Auth::user() : null;
        if (is_null($curr_user)) {
            //当前访问者未登录，被访问者访问记录+1
            Event::fire(new HomepageViewEvent($UserData));
        } else if ($curr_user->id != $user->id) {
            //当前访问者已登录且访问非登录者主页，被访问者访问记录+1
            Event::fire(new HomepageViewEvent($UserData));
        }

        //个人动态信息
        $per_dyns = PersonalDynamic::where('user_id', $user->id)->get();

        return view('pc.user.homepage.index')->with(['user' => $user, 'UserData' => $UserData, 'per_dyns' => $per_dyns]);
    }

    /**
     * 用户个人主页之我的问答
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questions($personal_domain)
    {
        //通过唯一的个性域名获取用户
        $user = User::where('personal_domain', $personal_domain)->first();
        //获取用户数据
        $UserData = $user->userData;
        //获取用户问答
        $questions = $user->questions;

        return view('pc.user.homepage.questions')->with(['user' => $user, 'UserData' => $UserData, 'questions' => $questions]);
    }

    /**
     * 用户个人主页之我的回复
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function answers($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        //获取用户回答
        $answers = $user->answers;

        return view('pc.user.homepage.answers')->with(['user' => $user, 'UserData' => $UserData, 'answers' => $answers]);
    }

    /**
     * 用户个人主页之我的文章
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogs($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        //获取用户博客
        $blogs = $user->blogs;

        return view('pc.user.homepage.blogs')->with(['user' => $user, 'UserData' => $UserData, 'blogs' => $blogs]);
    }

    /**
     * 用户个人主页之我的关注
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function attentions($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        //关注的用户
        $atte_users = $user->atte_user;
        //关注的问答
        $atte_ques = $user->atte_ques;

        return view('pc.user.homepage.attentions')->with(['user' => $user, 'UserData' => $UserData, 'atte_ques' => $atte_ques, 'atte_users' => $atte_users]);
    }

    /**
     * 用户个人主页之我的粉丝
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fans($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        $fans = Attention::where('entityable_id', $user->id)->where('entityable_type', get_class($user))->get();

        return view('pc.user.homepage.fans')->with(['user' => $user, 'UserData' => $UserData, 'fans' => $fans]);
    }

    /**
     * 用户个人主页之我的支持
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function supports($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        //支持的回答
        $supp_answers = $user->supp_answer;
        //反对的回答
        $oppo_answers = $user->oppo_answer;
        //点赞的博客
        $like_blogs = $user->like_blog;

        return view('pc.user.homepage.supports')->with(['user' => $user, 'UserData' => $UserData, 'supp_answers' => $supp_answers, 'oppo_answers' => $oppo_answers, 'like_blogs' => $like_blogs]);
    }

    /**
     * 用户个人主页之我的收藏
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collections($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        //收藏的博客
        $coll_blogs = $user->coll_blog;
        //收藏的问答
        $coll_ques = $user->coll_ques;

        return view('pc.user.homepage.collections')->with(['user' => $user, 'UserData' => $UserData, 'coll_ques' => $coll_ques, 'coll_blogs' => $coll_blogs]);
    }

    /**
     * 用户个人主页之我的草稿
     *
     * @param $personal_domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function drafts($personal_domain)
    {
        $user = User::where('personal_domain', $personal_domain)->first();
        $UserData = $user->userData;
        //问答草稿
        $questions = Question::where('user_id', $user->id)->where('status', 2)->get();
        //博客草稿
        $blogs = Blog::where('user_id', $user->id)->where('status', 2)->get();

        return view('pc.user.homepage.drafts')->with(['user' => $user, 'UserData' => $UserData, 'questions' => $questions, 'blogs' => $blogs]);
    }

    /**
     * 用户个人主页之关注用户
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function attention_user(Request $request)
    {
        //获取被关注用户id
        $user = $request->input('user');
        $users = User::where('id', $user)->first();
        $UserData = $user->userData;
        //获取当前用户id
        $curr_user = $request->input('curr_user');
        $attention = Attention::where('user_id', $curr_user)->where('entityable_id', $user)->where('entityable_type', get_class($users))->first();
        $curr_UserData = UserData::where('user_id', $curr_user)->first();

        if ($attention) {
            //如存在此用户关注该用户记录，则属于取消关注
            $att_del = $attention->delete();
            if ($att_del == true) {
                //当前用户关注数-1
                $curr_UserData->decrement('attention_count');
                //被关注用户粉丝数-1
                $UserData->decrement('fan_count');

                return 'unattention';
            }
        } else {
            //如不存在此用户关注该用户记录，则属于关注
            $data = [
                'user_id' => $curr_user,
                'entityable_id' => $user,
                'entityable_type' => get_class($users),
            ];
            $attention_user = Attention::create($data);
            if ($attention_user) {
                //当前用户关注数+1
                $curr_UserData->increment('attention_count');
                //被关注用户粉丝数+1
                $UserData->increment('fan_count');
                return 'attention';
            }
        }
        return $this->jsonResult(500);
    }

    /**
     * 用户个人设置之个人信息
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('pc.user.settings.setting')->with(['user' => $user, 'taxonomies' => self::get_careerStatus($user->career_direction)]);
    }

    /**
     * 用户个人设置之实名认证
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function authenticate(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        $user_auth = UserAuthenticate::where('user_id', $user->id)->first();
        if ($user_auth) {
            return view('pc.user.settings.authenticate')->with(['user' => $user, 'user_auth' => $user_auth]);
        }
        return view('pc.user.settings.authenticate')->with(['user' => $user]);
    }

    /**
     * 用户个人设置之密码修改
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_password(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('pc.user.settings.edit_password')->with(['user' => $user]);
    }

    /**
     * 用户个人设置之通知私信
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_notify(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('pc.user.settings.edit_notify')->with(['user' => $user]);
    }

    /**
     * 用户个人设置之账号安全
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('pc.user.settings.security')->with(['user' => $user]);
    }

    /**
     * 用户个人设置之账号绑定
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bindsns(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('pc.user.settings.bindsns')->with(['user' => $user]);
    }

    /**
     * 用户个人设置之职业状态
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function career_status(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('pc.user.partials.career_status')->with(['user' => $user]);
    }

    /**
     * 保存用户个人信息
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function per_detail(Request $request)
    {
        //dd($request->file('wechat_qrcode'));
        if (Auth::check()) {
            $user = Auth::user();
            $cur_user = User::where('id', $user->id)->first();
            $cur_user->avatar = $request->input('avatar_image');
            $cur_user->username = $request->input('username');
            $cur_user->realname = $request->input('realname');
            $cur_user->gender = $request->input('gender');
            $cur_user->birthday = $request->input('birthday');
            $cur_user->birthday = $request->input('birthday');

            $picker = $request->input('city-picker');
            $array = explode('/', $picker);
            $cur_user->province = isset($array[0]) ? $array[0] : 0;
            $cur_user->city = isset($array[1]) ? $array[1] : 0;

            $cur_user->personal_domain = $request->input('personal_domain');
            $cur_user->personal_website = $request->input('personal_website');
            $cur_user->description = $request->input('description');

            $file = $request->file('wechat_qrcode');
            if ($file != null) {
                $validateRules = [
                    'wechat_qrcode' => 'required|image|max:' . config('global.upload.image.max_size'),
                ];
                $this->validate($request, $validateRules);
                $extension = $file->getClientOriginalExtension();

                if (config('global.aliyun_oss')) {
                    //上传到阿里云oss


                    //若之前上传了微信二维码图片，则先删除在添加新的图片
                    if ($cur_user->wechat_qrcode != null) {
                        File::delete(storage_path('app/' . $cur_user->wechat_qrcode));
                    }
                    //上传到本地服务器
                    $filePath = config('global.upload_folder') . DIRECTORY_SEPARATOR . 'wechat_qrcode' . DIRECTORY_SEPARATOR . \Auth::user()->id . DIRECTORY_SEPARATOR . uniqid(str_random(8)) . '.' . $extension;
                    Storage::disk('local')->put($filePath, File::get($file));
                    Image::make(storage_path('app/' . $filePath))->resize(180, 180)->save();
                } else {
                    //若之前上传了微信二维码图片，则先删除在添加新的图片
                    if ($cur_user->wechat_qrcode != null) {
                        File::delete(storage_path('app/' . $cur_user->wechat_qrcode));
                    }

                    //只上传到本地服务器
                    $filePath = config('global.upload_folder') . DIRECTORY_SEPARATOR . 'wechat_qrcode' . DIRECTORY_SEPARATOR . \Auth::user()->id . DIRECTORY_SEPARATOR . uniqid(str_random(8)) . '.' . $extension;
                    Storage::disk('local')->put($filePath, File::get($file));
                    Image::make(storage_path('app/' . $filePath))->resize(180, 180)->save();
                }
                $cur_user->wechat_qrcode = $filePath;
            }

            $cur_user->career_status = $request->input('career_status');
            $career_direction = $request->input('career_direction');
            if ($career_direction != 0) {
                $career_directions = CareerDirection::where('id', $career_direction)->first();
                $cur_user->career_direction = $career_directions->name;
            } else {
                $cur_user->career_direction = null;
            }

            $cur_user->save();
            return $this->success(route('user.settings', ['username' => $user->username]), '个人信息修改成功！！！');
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 修改用户头像
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_avatar(Request $request)
    {
        $validateRules = [
            'user_avatar' => 'required|image',
        ];

        //保存选择图片后自动上传的图片
        if ($request->hasFile('user_avatar')) {
            $this->validate($request, $validateRules);
            $user_id = \Auth::user()->id;
            $file = $request->file('user_avatar');
            $avatarDir = User::getAvatarDir($user_id);
            $extension = strtolower($file->getClientOriginalExtension());
            $extArray = array('png', 'gif', 'jpeg', 'jpg');

            if (in_array($extension, $extArray)) {
                //上传到七牛云Kodo
                if (config('global.qiniu_kodo')) {
                    $qiniu = new QiNiuCloud();

                    $path = $file->store($avatarDir);
                    $qiniu->qiniu_upload($path);

                    //上传到本地服务器
                    if ($extension != 'jpg') {
                        Image::make(File::get($file))->save(storage_path('app/' . User::getAvatarPath($user_id, 'origin')));
                    } else {
                        Storage::disk('local')->put($avatarDir . DIRECTORY_SEPARATOR . User::getAvatarFileName($user_id, 'origin') . '.' . $extension, File::get($file));
                    }
                } else {
                    //只上传到本地服务器
                    if ($extension != 'jpg') {
                        Image::make(File::get($file))->save(storage_path('app/' . User::getAvatarPath($user_id, 'origin')));
                    } else {
                        Storage::disk('local')->put($avatarDir . DIRECTORY_SEPARATOR . User::getAvatarFileName($user_id, 'origin') . '.' . $extension, File::get($file));
                    }
                }
            } else {
                //return response('errors');
                return $this->jsonResult(603);
            }

            /*return response()->json(array(
                'status' => 1,
                'msg' => '头像上传成功'
            ));*/
            return $this->jsonResult(601);
        }

        //保存点击保存图片按钮后上传的裁剪图片
        if ($request->isMethod('POST')) {
            $x = intval($request->input('x'));
            $y = intval($request->input('y'));
            $width = intval($request->input('width'));
            $height = intval($request->input('height'));

            $user_id = $request->user()->id;

            File::delete(storage_path('app/' . User::getAvatarPath($user_id, 'big')));
            File::delete(storage_path('app/' . User::getAvatarPath($user_id, 'medium')));
            File::delete(storage_path('app/' . User::getAvatarPath($user_id, 'middle')));
            File::delete(storage_path('app/' . User::getAvatarPath($user_id, 'small')));

            //上传到阿里云oss
            if (config('global.aliyun_oss')) {
                /*OSS::publicUpload(config('global.aliyun_oss_bucket'), $avatarDir . '/' . User::getAvatarFileName($user_id,'origin'), $file, [
                    //'ContentType' => 'image/png'
                    'ContentType' => $file->getMimeType()
                ]);*/

                //上传到本地服务器
                //crop():创建一个新的剪裁区域;resize():设置裁剪的图片大小;
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(24, 24)->save(storage_path('app/' . User::getAvatarPath($user_id, 'small')));
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(46, 46)->save(storage_path('app/' . User::getAvatarPath($user_id, 'medium')));
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(64, 64)->save(storage_path('app/' . User::getAvatarPath($user_id, 'middle')));
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(128, 128)->save(storage_path('app/' . User::getAvatarPath($user_id, 'big')));
            } else {
                //只上传到本地服务器
                //crop():创建一个新的剪裁区域;resize():设置裁剪的图片大小;
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(24, 24)->save(storage_path('app/' . User::getAvatarPath($user_id, 'small')));
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(46, 46)->save(storage_path('app/' . User::getAvatarPath($user_id, 'medium')));
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(64, 64)->save(storage_path('app/' . User::getAvatarPath($user_id, 'middle')));
                Image::make(storage_path('app/' . User::getAvatarPath($user_id, 'origin')))->crop($width, $height, $x, $y)->resize(128, 128)->save(storage_path('app/' . User::getAvatarPath($user_id, 'big')));
            }

            /*return response()->json(array(
                'status' => 1,
                'msg' => '头像截剪成功'
            ));*/

            return $this->jsonResult(602, config('errors.' . '602'), config('errors.' . '605'));
        }
    }

    /**
     * 修改用户密码
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function modify_password(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cur_user = User::where('id', $user->id)->first();

            $input = $request->only(['old_password', 'new_password', 'password_confirmation']);
            $rules = [
                'old_password' => 'required|between:6,20',
                'new_password' => 'required|between:6,20|different:old_password',
                'password_confirmation' => 'required|same:new_password',
            ];
            //自定义验证信息：1.可定义message数组，把自定义信息定义并当参数放入Validator::make()；2.在Validation.php文件custom定义验证信息
            /*$message = [
                'different' => ':attribute 和 :other 不能相同...',
            ];*/
            $validator = Validator::make($input, $rules/*, $message*/);


            if ($validator->fails()) {
                //两种写法，back()函数返回到上一个请求的位置
                return redirect(route('user.edit_password', ['username', $cur_user->username]))->withInput()->withErrors($validator);
                //return redirect()->back()->withInput()->withErrors($validator);
            } else if (!Hash::check($input['old_password'], $cur_user->password)) {   //判断原密码是否正确
                return redirect(route('user.edit_password', ['username', $cur_user->username]))->withInput()->withErrors(['old_password' => '原密码错误！！！']);
            } else {
                $cur_user->password = bcrypt($input['new_password']);
                $cur_user->save();
                Auth::logout();
                //return $this->success(route('user.edit_password',['username', $cur_user->username]), '密码修改成功！！！');
                return $this->success(route('login'), '密码修改成功，请重新登录...');
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 绑定邮箱
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function email_bind(Request $request)
    {
        if (Auth::check()) {
            $email = $request->input('new_email');
            $user_email = User::where('email', $email)->first();
            $rules = array(
                'new_email' => 'required|string|email',
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else if ($user_email) {
                //用户输入邮箱已存在
                return $this->jsonResult(908);
            } else if (!$user_email) {
                //用户输入邮箱不存在，则直接绑定
                $user = User::where('id', Auth::user()->id)->first();
                $user->email = $email;
                $bool = $user->save();

                if ($bool == true) {
                    /*$user_code = json_encode(['uid' => $user->id,'username' => $user->username, 'email' => $email, 'time' => time()]);
                    $sign_code = hash_hmac('sha256', Str::random(40), Config::get('key'));
                    $verification_token = str_random(6) . '-' . md5($user_code);

                    $data = [
                        'email'             => $email,
                        'username'          => $user->username,
                        'uid'               => $user->id,
                        'sign_code'         => $sign_code,
                        'user_code'         => $user_code,
                        'verification_token'=> $verification_token,
                        'email_verify_url'  => url('user/email_bind/verify') . '?s=' . urlencode($sign_code) . '&u=' . urlencode($user_code) . '&v=' . $verification_token
                    ];*/
                    //方法一：发送纯文本格式
                    /*Mail::raw('Serenity 邮件测试(邮件内容)', function ($message) use ($email) {
                        $message->from('发送邮件邮箱', '发件人');
                        $message->subject('邮件主题');
                        $message->to('收件人');
                    });*/

                    //方法二：发送HTML格式
                    //验证通过后发送邮件
                    // 生成唯一 token
                    $token = bcrypt($email . time());

                    $data = [
                        'email' => $email,
                        'user' => $user,
                        'token' => $token,
                        'email_verify_url' => url('user/email_bind/verify') . '?v=' . $token
                    ];

                    Mail::send('user.partials.email_verify', ['data' => $data], function ($message) use ($data) {
                        $message->subject('Laraveler 邮箱绑定验证');
                        $message->to($data['email']);
                    });

                    // 数据库保存 token
                    if ($user->activations) {
                        $user->activations()->update(['token' => $token]);
                    } else {
                        $user->activations()->save(new UserActivation([
                            'token' => $token
                        ]));
                    }

                    return $this->jsonResult(909, '邮箱地址绑定成功，请前往-> ' . $email . ' <-验证', $email);
                } else {
                    //邮箱绑定失败
                    return $this->jsonResult(910);
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 验证邮箱（重新发送验证邮件）
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function send_verify(Request $request)
    {
        if (Auth::check()) {
            $email = $request->input('email');
            $user = User::where('email', $email)->first();
            $rules = array(
                'email' => 'required|string|email',
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->jsonResult(502, $validator->errors()->all());
            } else {
                // 生成唯一 token
                $token = bcrypt($email . time());

                $data = [
                    'email' => $email,
                    'user' => $user,
                    'token' => $token,
                    'email_verify_url' => url('user/email_bind/verify') . '?v=' . $token
                ];

                Mail::send('user.partials.email_verify', ['data' => $data], function ($message) use ($data) {
                    $message->subject('Laraveler - 中文领域的Laravel技术问答交流社区邮箱绑定验证');
                    $message->to($data['email']);
                });

                // 数据库保存 token
                if ($user->activations) {
                    $user->activations()->update(['token' => $token]);
                } else {
                    $user->activations()->save(new UserActivation([
                        'token' => $token
                    ]));
                }

                return $this->jsonResult(909, '一封验证邮件已发送至' . $email . '，请前往此邮箱进行验证 ^_^');
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 激活邮箱
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activate_email_bind(Request $request)
    {
        $data = $request->all();
        $token = $data['v'];

        //Carbon::now()->subDay()：默认参数为1（天数），也就是获取昨天当前时刻
        $user_activation = UserActivation::where('token', $token)->whereBetween('updated_at', [Carbon::now()->subDay(), Carbon::now()])->where('active', 1)->first();
        if ($user_activation) {
            $user_activation->active = 0;
            $bool = $user_activation->save();
            if ($bool == true) {
                $user = User::where('id', $user_activation->user_id)->first();
                $user->email_status = 1;
                $user->save();
            }

            return view('pc.user.partials.email_verify_callback')->with(['status' => 1]);
        } else {
            $u_a = UserActivation::where('token', $token)->whereBetween('updated_at', [Carbon::now()->subDay(), Carbon::now()])->first();
            if (isset($u_a) && $u_a->active == 0) {
                return view('pc.user.partials.email_verify_callback')->with(['status' => 2]);
            } else {
                return view('pc.user.partials.email_verify_callback')->with(['status' => 3]);
            }
        }
    }

    /**
     * 更换邮箱前验证
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify_email(Request $request)
    {
        if (Auth::check()) {
            $email = $request->input('email');
            $user = User::where('id', Auth::user()->id)->first();
            //验证前先判断用户邮箱是否存在
            if ($user->email) {
                //用户邮箱存在，则验证邮箱
                if (strcmp($user->email, $email) == 0) {
                    return $this->jsonResult(890);
                } else {
                    return $this->jsonResult(891);
                }
            } else {
                //用户邮箱不存在
                return $this->jsonResult(892);
            }
        } else {
            return $this->jsonResult(503);
        }
    }

    /**
     * 绑定手机号并发送验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mobile_bind(Request $request)
    {
        $data = $request->all();
        $user = User::where('id', Auth::user()->id)->first();

        //验证手机号
        $validator = Validator::make($data, [
            'mobile' => 'required|string|min:11|regex:/^1[34578][0-9]{9}$/',
        ]);
        if ($validator->fails()) {
            //验证失败后建议清空存储的发送状态，防止用户重复试错
            SmsManager::forgetState();
            return $this->jsonResult(502, $validator->errors()->all());
        } else if (strcmp($user->mobile, $data['mobile']) != 0) {
            //当前用户手机号与输入绑定手机号不相同，有可能用户换了注册手机号，此时需要查询输入绑定手机号是否存在，如存在，返回提示；不存在则往下保存并发送验证码
            $user_mobile = User::where('mobile', $data['mobile'])->first();
            if ($user_mobile) {
                return $this->jsonResult(896);
            }
        } else {
            //短信接口请求参数
            $appid = env('AppID');
            $templateid = env('Template_Id_Bind');

            $options['accountsid'] = env('Account_Sid');
            $options['token'] = env('Auth_Token');
            $ucpass = new UcpaasAgent($options);

            //获取手机号
            $mobile = $data['mobile'];

            $verify_code = '';
            for ($i = 0; $i < 6; $i++) {
                $verify_code .= random_int(0, 9);
            }

            $param = "$verify_code,5";

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
            $back_data = json_decode($data, true);

            if ($back_data['code'] == '000000') {
                //发送成功，把短信验证码保存在缓存 key：手机号，value：验证码随机数
                Cache::put($request->input('mobile'), $verify_code, 5);
                Cache::put($request->input('mobile') . 'minute', 1, 1);

                return $this->jsonResult(900);
            } else {
                //发送失败
                return $this->jsonResult(901);
            }
        }
    }

    /**
     * 更换绑定手机号并发送验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_mobile_bind(Request $request)
    {
        $data = $request->all();
        $user_mobile = User::where('mobile', $data['new_mobile'])->first();
        //验证手机号
        $validator = Validator::make($data, [
            'new_mobile' => 'required|string|min:11|regex:/^1[34578][0-9]{9}$/',
        ]);
        if ($validator->fails()) {
            //验证失败后建议清空存储的发送状态，防止用户重复试错
            SmsManager::forgetState();
            return $this->jsonResult(502, $validator->errors()->all());
        } else if ($user_mobile) {
            //如存在，返回提示；不存在则往下保存并发送验证码
            return $this->jsonResult(896);
        } else {
            //短信接口请求参数
            $appid = env('AppID');
            $templateid = env('Template_Id_Change');

            $options['accountsid'] = env('Account_Sid');
            $options['token'] = env('Auth_Token');
            $ucpass = new UcpaasAgent($options);

            //获取手机号
            $mobile = $data['new_mobile'];

            $verify_code = '';
            for ($i = 0; $i < 6; $i++) {
                $verify_code .= random_int(0, 9);
            }

            $param = "$verify_code,5";

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
            $back_data = json_decode($data, true);

            if ($back_data['code'] == '000000') {
                //发送成功，把短信验证码保存在缓存 key：手机号，value：验证码随机数
                Cache::put($request->input('new_mobile'), $verify_code, 5);
                Cache::put($request->input('new_mobile') . 'minute', 1, 1);

                return $this->jsonResult(900);
            } else {
                //发送失败
                return $this->jsonResult(901);
            }
        }
    }

    /**
     * 验证用户提交的手机验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify_mobile_code(Request $request)
    {
        $data = $request->all();
        //验证手机验证码
        $validator = Validator::make($data, [
            'verify_code' => 'required|validateMobile:' . $data['mobile'],
        ]);
        if ($validator->fails()) {
            //验证失败后建议清空存储的发送状态，防止用户重复试错
            SmsManager::forgetState();
            return $this->jsonResult(502, $validator->errors()->all());
        } else {
            $user = User::where('id', Auth::user()->id)->first();
            $user->mobile = $data['mobile'];
            $user->mobile_status = 1;
            $bool = $user->save();

            if ($bool == true) {
                //绑定成功，删除此用户的短信验证码缓存
                Cache::forget($data['mobile']);
                Cache::forget($data['mobile'] . 'minute');

                return $this->jsonResult(898, '恭喜您，手机号码绑定成功 ^_^', $user->username);
            } else {
                return $this->jsonResult(897);
            }
        }
    }

    /**
     * 更换手机前验证
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify_mobile(Request $request)
    {
        if (Auth::check()) {
            $old_mobile = $request->input('old_mobile');
            $user = User::where('id', Auth::user()->id)->first();
            //验证前先判断用户手机号码是否存在
            if ($user->mobile) {
                //用户手机号码存在，则验证号码
                if (strcmp($user->mobile, $old_mobile) == 0) {
                    return $this->jsonResult(893);
                } else {
                    return $this->jsonResult(894);
                }
            } else {
                //用户手机号码不存在
                return $this->jsonResult(895);
            }
        } else {
            return $this->jsonResult(503);
        }
    }

    /**
     * 社交账号解除绑定
     *
     * @param Request $request
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function social_unbind(Request $request, $driver)
    {
        $redirect_uri = $request->get('redirect_uri');
        $user = Auth::user();
        $us = UserSocialite::where('user_id', $user->id)->where('oauth_type', $driver)->first();
        if ($us) {
            $bool = $us->delete();
            if ($bool == true) {
                return $this->success($redirect_uri, '社交账号解除绑定成功 ^_^');
            } else {
                return $this->error($redirect_uri, '社交账号解除绑定失败');
            }
        }
    }

    /**
     * 通知私信
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notify(Request $request)
    {
        $type = $request->get('type');
        if (Auth::check()) {
            $user = User::where('id', Auth::user()->id)->first();
            switch ($type) {
                case 0:
                    if ($user->ques_ans_notifications == 0) {
                        $user->ques_ans_notifications = 1;
                        $user->save();
                    } else {
                        $user->ques_ans_notifications = 0;
                        $user->save();
                    }
                    break;
                case 1:
                    if ($user->blog_comm_notifications == 0) {
                        $user->blog_comm_notifications = 1;
                        $user->save();
                    } else {
                        $user->blog_comm_notifications = 0;
                        $user->save();
                    }
                    break;
                case 2:
                    if ($user->user_atte_notifications == 0) {
                        $user->user_atte_notifications = 1;
                        $user->save();
                    } else {
                        $user->user_atte_notifications = 0;
                        $user->save();
                    }
                    break;
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 签到
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function signIn()
    {
        if (Auth::check()) {
            $credit_config = UserCreditConfig::where('slug', 'signIn')->first();
            $UserData = UserData::where('user_id', Auth::user()->id)->first();

            $data = [
                'user_id' => Auth::user()->id,
                'type' => $credit_config->slug,
                'credits' => $credit_config->credits,
            ];
            $credit_sta = UserCreditStatement::create($data);
            if ($credit_sta) {
                $UserData->credits = $UserData->credits + $credit_config->credits;
                $UserData->save();

                return response('signIn');
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 实名认证
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function post_authenticate(Request $request)
    {
        $input = $request->only('real_name', 'id_card', 'front', 'verso', 'hand', 'redirect_uri');
        $rule = [
            'front' => 'required|image',
            'verso' => 'required|image',
            'hand' => 'required|image',
        ];

        $validator = Validator::make($input, $rule);
        if ($input['real_name'] == null) {
            return $this->error($input['redirect_uri'], config('errors.915'));
        } else if ($input['id_card'] == null) {
            return $this->error($input['redirect_uri'], config('errors.916'));
        } else if ($validator->fails()) {
            return $this->error($input['redirect_uri'], config('errors.917'));
            //return back()->withInput()->withErrors($validator);
        } else if ($request->hasFile('front') && $request->hasFile('verso') && $request->hasFile('hand')) {
            $user_id = Auth::user()->id;
            //获取图片
            $front_file = $request->file('front');
            $verso_file = $request->file('verso');
            $hand_file = $request->file('hand');
            //图片保存相对路径
            $idcardDir = config('global.upload_folder') . '/' . 'idcard' . '/' . $user_id;
            //图片后缀名
            $front_exte = strtolower($front_file->getClientOriginalExtension());
            $verso_exte = strtolower($verso_file->getClientOriginalExtension());
            $hand_exte = strtolower($hand_file->getClientOriginalExtension());
            $extArray = array('png', 'gif', 'jpeg', 'jpg');

            if (in_array($front_exte, $extArray) && in_array($verso_exte, $extArray) && in_array($hand_exte, $extArray)) {
                //上传到七牛云Kodo
                if (config('global.qiniu_kodo')) {
                    $qiniu = new QiNiuCloud();
                    //上传七牛云相对路径定义
                    $front_part_path = $idcardDir . '/' . uniqid(str_random(16)) . '.' . $front_exte;
                    $verso_part_path = $idcardDir . '/' . uniqid(str_random(16)) . '.' . $verso_exte;
                    $hand_part_path = $idcardDir . '/' . uniqid(str_random(16)) . '.' . $hand_exte;

                    $path = $front_file->store($front_part_path);
                    $qiniu->qiniu_upload($path);

                    $path = $verso_file->store($verso_part_path);
                    $qiniu->qiniu_upload($path);

                    $path = $hand_file->store($hand_part_path);
                    $qiniu->qiniu_upload($path);

                    $data = [
                        'user_id' => $user_id,
                        'realname' => $input['real_name'],
                        'idcard' => $input['id_card'],
                        'front_img' => config('global.qiniu_url') . $front_part_path,
                        'verso_img' => config('global.qiniu_url') . $verso_part_path,
                        'hand_img' => config('global.qiniu_url') . $hand_part_path,
                        'status' => 0,
                        'feeback' => '实名认证资料提交成功，我们将在3个工作日内完成审核并反馈，请耐心等待并留意实名认证进度......',
                    ];
                    $user_auth = UserAuthenticate::create($data);
                    if ($user_auth) {
                        Auth::user()->approval_status = 1;
                        Auth::user()->save();

                        return redirect($input['redirect_uri']);
                    }
                } else {
                    //只上传到本地服务器
                    /*if ($extension != 'jpg') {
                        Image::make(File::get($file))->save(storage_path('app/' . User::getAvatarPath($user_id, 'origin')));
                    } else {
                        Storage::disk('local')->put($avatarDir . DIRECTORY_SEPARATOR . User::getAvatarFileName($user_id, 'origin') . '.' . $extension, File::get($file));
                    }*/
                }
            } else {
                return $this->error($input['redirect_uri'], config('errors.914'));
            }
        } else {

        }
    }

    /**
     * 获取职业方向以下拉菜单方式返回
     *
     * @param $ids
     * @return string
     */
    public function get_careerStatus($ids)
    {
        $taxonomies = '';
        $careers = CareerDirection::get();

        foreach ($careers as $career) {
            $taxonomies .= '<option value="' . $career->id . '"';
            if (strcasecmp($career->name, $ids) === 0) {
                $taxonomies .= ' selected';
            }
            $taxonomies .= '>' . $career->name . '</option>';
        }
        //dd($taxonomies);
        return $taxonomies;
    }

    /**
     * 活跃排行榜
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function active_rank()
    {
        $active_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status', '>', 0)
            ->orderBy('user_datas.answer_count', 'DESC')
            ->orderBy('user_datas.article_count', 'DESC')
            ->orderBy('user.updated_at', 'DESC')
            ->select('user.id', 'user.username', 'user.personal_domain', 'user.expert_status', 'user_datas.coins', 'user_datas.credits', 'user_datas.attention_count', 'user_datas.support_count', 'user_datas.answer_count', 'user_datas.article_count')
            ->take(10)->get();

        return view('pc.user.partials.active_rank')->with(['active_users' => $active_users]);
    }

    /**
     * 积分排行榜
     *
     * @return \Illuminate\Http\Response
     */
    public function credit_rank()
    {
        $credit_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status', '>', 0)
            ->orderBy('user_datas.credits', 'DESC')
            ->select('user.id', 'user.username', 'user.personal_domain', 'user.expert_status', 'user_datas.coins', 'user_datas.credits', 'user_datas.attention_count', 'user_datas.support_count', 'user_datas.answer_count', 'user_datas.article_count')
            ->take(10)->get();

        return view('pc.user.partials.credit_rank')->with(['credit_users' => $credit_users]);
    }
}
