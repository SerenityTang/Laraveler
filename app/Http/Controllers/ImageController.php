<?php

namespace App\Http\Controllers;

use App\Services\Qiniu\QiNiuCloud;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    /**
     * 显示用户头像
     * @param $avatar_name
     * @return mixed
     */
    public function avatar($avatar_name)
    {
        list($user_id, $size) = explode('_',str_replace("_jpg",'',$avatar_name));
        $avatarFile = storage_path('app/'.User::getAvatarPath($user_id, $size));
        if(!is_file($avatarFile)){
            $avatarFile = public_path('imgs/default_avatar.jpg');
        }
        $image = Image::make($avatarFile);
        return response()->make($image->encode('jpg'))->header('Content-Type', 'image/jpeg')->cookie('avatar_image', 'app/'.User::getAvatarPath($user_id, $size), 10);
    }

    //编辑器图片上传后显示
    public function show($image_name)
    {
        $imageFile = storage_path('app/'.str_replace("-","/",$image_name));
        if(!is_file($imageFile)){
            abort(404);
        }
        return Image::make($imageFile)->response();
    }

    //编辑器图片上传
    public function upload(Request $request)
    {
        $type_id = $request->input('type_id');
        $image_type = $_GET['image_type'];
        $validateRules = [
            'file' => 'required|image|max:'.config('global.upload.image.max_size'),
        ];

        if($request->hasFile('file')){
            $this->validate($request,$validateRules);
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            //上传到七牛云Kodo
            if (config('global.qiniu_kodo')) {
                $qiniu = new QiNiuCloud();
                $filePath = config('global.upload_folder'). DIRECTORY_SEPARATOR .$image_type. DIRECTORY_SEPARATOR .\Auth::user()->id. DIRECTORY_SEPARATOR .'typeid_'.$type_id.'_'.uniqid(str_random(8)).'.'.$extension;
                Storage::disk('local')->put($filePath, File::get($file));
                $qiniu->qiniu_upload($filePath);

                return config('global.qiniu_url') . $filePath;
            } else {
                //只上传到本地服务器
                $filePath = config('global.upload_folder'). DIRECTORY_SEPARATOR .$image_type. DIRECTORY_SEPARATOR .\Auth::user()->id. DIRECTORY_SEPARATOR .uniqid(str_random(8)).'.'.$extension;
                Storage::disk('local')->put($filePath, File::get($file));

                return response(route("image.show",['image_name'=>str_replace("/","-",$filePath)]));
            }
        }
        return response('errors');
    }
}
