<?php

namespace App\Http\Controllers;

use App\Events\HomepageViewEvent;
use App\Models\Attention;
use App\Models\Blog;
use App\Models\Career_direction;
use App\Models\Question;
use App\Models\User_data;
use App\Models\UserActivation;
use App\User;
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
//use App\Services\OSS;

class UserController extends Controller
{
    //用户个人主页
    public function index($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        //统计主页被访问记录
        $curr_user = Auth::check() ? Auth::user() : null;
        if ($curr_user == null) {
            Event::fire(new HomepageViewEvent($user_data));
        } else if ($curr_user->id != $user->id) {
            Event::fire(new HomepageViewEvent($user_data));
        }

        return view('user.homepage.index')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的问答
    public function questions($personal_domain) {
        //通过唯一的个性域名获取用户
        $user = User::where('personal_domain', $personal_domain)->first();
        //获取用户数据
        $user_data = User_data::where('user_id', $user->id)->first();
        //获取用户问答
        $questions = $user->questions;

        return view('user.homepage.questions')->with(['user' => $user, 'user_data' => $user_data, 'questions' => $questions]);
    }

    //用户个人主页之我的回复
    public function answers($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();
        //获取用户回答
        $answers = $user->answers;

        return view('user.homepage.answers')->with(['user' => $user, 'user_data' => $user_data, 'answers' => $answers]);
    }

    //用户个人主页之我的文章
    public function blogs($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();
        //获取用户博客
        $blogs = $user->blogs;

        return view('user.homepage.blogs')->with(['user' => $user, 'user_data' => $user_data, 'blogs' => $blogs]);
    }

    //用户个人主页之我的关注
    public function attentions($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        //关注的用户
        $atte_users = $user->atte_user;

        //关注的问答
        $atte_ques = $user->atte_ques;

        return view('user.homepage.attentions')->with(['user' => $user, 'user_data' => $user_data, 'atte_ques' => $atte_ques, 'atte_users' => $atte_users]);
    }

    //用户个人主页之我的粉丝
    public function fans($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();
        $fans = Attention::where('entityable_id', $user->id)->where('entityable_type', get_class($user))->get();

        return view('user.homepage.fans')->with(['user' => $user, 'user_data' => $user_data, 'fans' => $fans]);
    }

    //用户个人主页之我的支持
    public function supports($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        //支持的回答
        $supp_answers = $user->supp_answer;

        //反对的回答
        $oppo_answers = $user->oppo_answer;

        //点赞的博客
        $like_blogs = $user->like_blog;

        return view('user.homepage.supports')->with(['user' => $user, 'user_data' => $user_data, 'supp_answers' => $supp_answers, 'oppo_answers' => $oppo_answers, 'like_blogs' => $like_blogs]);
    }

    //用户个人主页之我的收藏
    public function collections($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        //收藏的博客
        $coll_blogs = $user->coll_blog;

        //收藏的问答
        $coll_ques = $user->coll_ques;

        return view('user.homepage.collections')->with(['user' => $user, 'user_data' => $user_data, 'coll_ques' => $coll_ques, 'coll_blogs' => $coll_blogs]);
    }

    //用户个人主页之我的草稿
    public function drafts($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        //问答草稿
        $questions = Question::where('user_id', $user->id)->where('status', 2)->get();

        //博客草稿
        $blogs = Blog::where('user_id', $user->id)->where('status', 2)->get();

        return view('user.homepage.drafts')->with(['user' => $user, 'user_data' => $user_data, 'questions' => $questions, 'blogs' => $blogs]);
    }

    //用户个人主页之关注用户
    public function attention_user(Request $request) {
        //获取被关注用户id
        $user = $request->input('user');
        $users = User::where('id', $user)->first();
        $user_data = User_data::where('user_id', $user)->first();
        //获取当前用户id
        $curr_user = $request->input('curr_user');
        $attention = Attention::where('user_id', $curr_user)->where('entityable_id', $user)->where('entityable_type', get_class($users))->first();
        $curr_user_data = User_data::where('user_id', $curr_user)->first();

        if ($attention) {
            //如存在此用户关注该用户记录，则属于取消关注
            $att_del = $attention->delete();
            if ($att_del == true) {
                //当前用户关注数-1
                $curr_user_data->decrement('attention_count');
                //被关注用户粉丝数-1
                $user_data->decrement('fan_count');

                return 'unattention';
            }
        } else {
            //如不存在此用户关注该用户记录，则属于关注
            $data = [
                'user_id'           =>$curr_user,
                'entityable_id'     =>$user,
                'entityable_type'   =>get_class($users),
            ];
            $attention_user = Attention::create($data);
            if ($attention_user) {
                //当前用户关注数+1
                $curr_user_data->increment('attention_count');
                //被关注用户粉丝数+1
                $user_data->increment('fan_count');
                return 'attention';
            }
        }
        return $this->jsonResult(500);
    }

    //用户个人设置之个人信息
    public function settings(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.settings.setting')->with(['user' => $user, 'taxonomies' => self::get_careerStatus($user->career_direction)]);
    }

    //用户个人设置之实名认证
    public function authenticate(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.settings.authenticate')->with(['user' => $user]);
    }

    //用户个人设置之密码修改
    public function edit_password(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.settings.edit_password')->with(['user' => $user]);
    }

    //用户个人设置之通知私信
    public function edit_notify(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.settings.edit_notify')->with(['user' => $user]);
    }

    //用户个人设置之账号安全
    public function security(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.settings.security')->with(['user' => $user]);
    }

    //用户个人设置之账号绑定
    public function bindsns(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.settings.bindsns')->with(['user' => $user]);
    }

    //用户个人设置之职业状态
    public function career_status(Request $request) {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        return view('user.partials.career_status')->with(['user' => $user]);
    }

    /**
     * 保存用户个人信息
     * @param Request $request
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
            if($file != null){
                $validateRules = [
                    'wechat_qrcode' => 'required|image|max:'.config('global.upload.image.max_size'),
                ];
                $this->validate($request,$validateRules);
                $extension = $file->getClientOriginalExtension();

                if (config('global.aliyun_oss')) {
                    //上传到阿里云oss


                    //若之前上传了微信二维码图片，则先删除在添加新的图片
                    if ($cur_user->wechat_qrcode != null) {
                        File::delete(storage_path('app/'.$cur_user->wechat_qrcode));
                    }
                    //上传到本地服务器
                    $filePath = config('global.upload_folder'). DIRECTORY_SEPARATOR .'wechat_qrcode'. DIRECTORY_SEPARATOR .\Auth::user()->id. DIRECTORY_SEPARATOR .uniqid(str_random(8)).'.'.$extension;
                    Storage::disk('local')->put($filePath,File::get($file));
                    Image::make(storage_path('app/'.$filePath))->resize(180, 180)->save();
                } else {
                    //若之前上传了微信二维码图片，则先删除在添加新的图片
                    if ($cur_user->wechat_qrcode != null) {
                        File::delete(storage_path('app/'.$cur_user->wechat_qrcode));
                    }

                    //只上传到本地服务器
                    $filePath = config('global.upload_folder'). DIRECTORY_SEPARATOR .'wechat_qrcode'. DIRECTORY_SEPARATOR .\Auth::user()->id. DIRECTORY_SEPARATOR .uniqid(str_random(8)).'.'.$extension;
                    Storage::disk('local')->put($filePath,File::get($file));
                    Image::make(storage_path('app/'.$filePath))->resize(180, 180)->save();
                }
                $cur_user->wechat_qrcode = $filePath;
            }

            $cur_user->career_status = $request->input('career_status');
            $career_direction = $request->input('career_direction');
            if ($career_direction != 0) {
                $career_directions = Career_direction::where('id', $career_direction)->first();
                $cur_user->career_direction = $career_directions->name;
            } else {
                $cur_user->career_direction = null;
            }

            $cur_user->save();
            return $this->success(route('user.settings', ['username' => $user->username]),'个人信息修改成功！！！');
        } else {
            return view('auth.login');
        }
    }

    /**
     * 修改用户头像
     * @param Request $request
     */
    public function post_avatar(Request $request)
    {
        $validateRules = [
            'user_avatar' => 'required|image',
        ];

        //保存选择图片后自动上传的图片
        if($request->hasFile('user_avatar')) {
            $this->validate($request,$validateRules);
            $user_id = \Auth::user()->id;
            $file = $request->file('user_avatar');
            $avatarDir = User::getAvatarDir($user_id);
            $extension = strtolower($file->getClientOriginalExtension());
            $extArray = array('png', 'gif', 'jpeg', 'jpg');

            if(in_array($extension, $extArray)) {
                //上传到阿里云oss
                if (config('global.aliyun_oss')) {
                    OSS::publicUpload(config('global.aliyun_oss_bucket'), $avatarDir . '/' . User::getAvatarFileName($user_id,'origin'), $file, [
                        //'ContentType' => 'image/png'
                        'ContentType' => $file->getMimeType()
                    ]);

                    //上传到本地服务器
                    if($extension != 'jpg'){
                        Image::make(File::get($file))->save(storage_path('app/'.User::getAvatarPath($user_id,'origin')));
                    }else{
                        Storage::disk('local')->put($avatarDir. DIRECTORY_SEPARATOR . User::getAvatarFileName($user_id,'origin').'.'.$extension, File::get($file));
                    }
                } else {
                    //只上传到本地服务器
                    if($extension != 'jpg'){
                        Image::make(File::get($file))->save(storage_path('app/'.User::getAvatarPath($user_id,'origin')));
                    }else{
                        Storage::disk('local')->put($avatarDir. DIRECTORY_SEPARATOR . User::getAvatarFileName($user_id,'origin').'.'.$extension, File::get($file));
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
        if($request->isMethod('POST')){
            $x = intval($request->input('x'));
            $y = intval($request->input('y'));
            $width = intval($request->input('width'));
            $height = intval($request->input('height'));

            $user_id = $request->user()->id;

            File::delete(storage_path('app/'.User::getAvatarPath($user_id,'big')));
            File::delete(storage_path('app/'.User::getAvatarPath($user_id,'medium')));
            File::delete(storage_path('app/'.User::getAvatarPath($user_id,'middle')));
            File::delete(storage_path('app/'.User::getAvatarPath($user_id,'small')));

            //上传到阿里云oss
            if (config('global.aliyun_oss')) {
                /*OSS::publicUpload(config('global.aliyun_oss_bucket'), $avatarDir . '/' . User::getAvatarFileName($user_id,'origin'), $file, [
                    //'ContentType' => 'image/png'
                    'ContentType' => $file->getMimeType()
                ]);*/

                //上传到本地服务器
                //crop():创建一个新的剪裁区域;resize():设置裁剪的图片大小;
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(24,24)->save(storage_path('app/'.User::getAvatarPath($user_id,'small')));
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(46,46)->save(storage_path('app/'.User::getAvatarPath($user_id,'medium')));
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(64,64)->save(storage_path('app/'.User::getAvatarPath($user_id,'middle')));
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(128,128)->save(storage_path('app/'.User::getAvatarPath($user_id,'big')));
            } else {
                //只上传到本地服务器
                //crop():创建一个新的剪裁区域;resize():设置裁剪的图片大小;
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(24,24)->save(storage_path('app/'.User::getAvatarPath($user_id,'small')));
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(46,46)->save(storage_path('app/'.User::getAvatarPath($user_id,'medium')));
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(64,64)->save(storage_path('app/'.User::getAvatarPath($user_id,'middle')));
                Image::make(storage_path('app/'.User::getAvatarPath($user_id,'origin')))->crop($width,$height,$x,$y)->resize(128,128)->save(storage_path('app/'.User::getAvatarPath($user_id,'big')));
            }

            /*return response()->json(array(
                'status' => 1,
                'msg' => '头像截剪成功'
            ));*/

            return $this->jsonResult(602, config('errors.'.'602'), config('errors.'.'605'));
        }
    }

    /**
     * 修改用户密码
     * @param Request $request
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
                return redirect(route('user.edit_password',['username', $cur_user->username]))->withInput()->withErrors($validator);
                //return redirect()->back()->withInput()->withErrors($validator);
            } else if (!Hash::check($input['old_password'], $cur_user->password)) {   //判断原密码是否正确
                return redirect(route('user.edit_password',['username', $cur_user->username]))->withInput()->withErrors(['old_password' => '原密码错误！！！']);
            } else {
                $cur_user->password = bcrypt($input['new_password']);
                $cur_user->save();
                Auth::logout();
                //return $this->success(route('user.edit_password',['username', $cur_user->username]), '密码修改成功！！！');
                return $this->success(route('login'), '密码修改成功，请重新登录...');
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 绑定邮箱
     * @param Request $request
     */
    public function email_bind(Request $request)
    {
        if (Auth::check()) {
            $email = $request->input('email');
            $user_email = User::where('email', $email)->first();
            $rules = array(
                'email' => 'required|string|email',
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
                    $token = bcrypt($email.time());

                    $data = [
                        'email'             => $email,
                        'user'              => $user,
                        'token'             => $token,
                        'email_verify_url'  => url('user/email_bind/verify') . '?v=' . $token
                    ];

                    Mail::send('user.partials.email_verify', ['data' => $data], function ($message) use ($data) {
                        $message->subject('Laraveler 邮箱绑定验证');
                        $message->to($data['email']);
                    });

                    // 数据库保存 token
                    if ($user->activations){
                        $user->activations()->update(['token' => $token, 'active' => 1]);
                    } else {
                        $user->activations()->save(new UserActivation([
                            'token' => $token
                        ]));
                    }

                    return $this->jsonResult(909, '邮箱地址绑定成功，请前往-> '. $email .' <-验证', $email);
                } else {
                    //邮箱绑定失败
                    return $this->jsonResult(910);
                }
            }
        }
    }

    /**
     * 激活邮箱
     * @param Request $request
     */
    public function activate_email_bind(Request $request)
    {
        $data = $request->all();
        $token = $data['v'];

        $user_activation = UserActivation::where('token', $token)->whereBetween('updated_at', [Carbon::now()->subDay(), Carbon::now()]);
        if ($user_activation) {
            $user_activation->active = 0;
            $user_activation->save();

            return view('user.partials.email_verify_callback')->with(['status' => 1]);
        } else {

            return view('user.partials.email_verify_callback')->with(['status' => 2]);
        }
    }

    /**
     * 获取职业方向以下拉菜单方式返回
     * @param $ids 职业名称
     */
    public function get_careerStatus($ids)
    {
        $taxonomies = '';
        $careers = Career_direction::get();

        foreach($careers as $career){
            $taxonomies .= '<option value="'. $career->id. '"';
            if(strcasecmp($career->name, $ids) === 0){
                $taxonomies .= ' selected';
            }
            $taxonomies .= '>'. $career->name .'</option>';
        }
        //dd($taxonomies);
        return $taxonomies;
    }

    /**
     * 活跃排行榜
     *
     * @return \Illuminate\Http\Response
     */
    public function active_rank()
    {
        $active_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.answer_count','DESC')
            ->orderBy('user_datas.article_count','DESC')
            ->orderBy('user.updated_at','DESC')
            ->select('user.id','user.username','user.personal_domain','user.expert_status','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count')
            ->take(10)->get();

        return view('user.partials.active_rank')->with(['active_users' => $active_users]);
    }

    /**
     * 积分排行榜
     *
     * @return \Illuminate\Http\Response
     */
    public function credit_rank()
    {
        $credit_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.credits','DESC')
            ->select('user.id','user.username','user.personal_domain','user.expert_status','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count')
            ->take(10)->get();

        return view('user.partials.credit_rank')->with(['credit_users' => $credit_users]);
    }
}
