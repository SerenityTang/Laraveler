<?php

namespace App\Http\Controllers;

use App\Models\Career_direction;
use App\Models\User_data;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
//use App\Services\OSS;

class UserController extends Controller
{
    //用户个人主页
    public function index($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.index')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的问答
    public function questions($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.questions')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的回复
    public function replies($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.replies')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的文章
    public function articles($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.articles')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的关注
    public function attentions($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.attentions')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的粉丝
    public function fans($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.fans')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的点赞
    public function likes($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.likes')->with(['user' => $user, 'user_data' => $user_data]);
    }

    //用户个人主页之我的收藏
    public function favorites($personal_domain) {
        $user = User::where('personal_domain', $personal_domain)->first();
        $user_data = User_data::where('user_id', $user->id)->first();

        return view('user.homepage.favorites')->with(['user' => $user, 'user_data' => $user_data]);
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
}
