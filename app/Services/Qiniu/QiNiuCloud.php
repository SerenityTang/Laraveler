<?php

/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/9
 * Time: 下午9:48
 */

namespace App\Services\Qiniu;

use Illuminate\Support\Facades\File;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiuCloud
{
    public function qiniu_upload($filePath)
    {//dd($filePath);
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = config('filesystems.disks.qiniu.access_key');
        $secretKey = config('filesystems.disks.qiniu.secret_key');
        $bucket = config('filesystems.disks.qiniu.bucket');

        $path = storage_path('app/' . $filePath);

        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        // $filePath = './php-logo.png';

        // 上传到七牛后保存的文件名
        /*$file_name = basename($filePath);
        preg_match('/\d+/', $filePath, $user);
        $key = explode($user[0], $filePath)[0] . $file_name;*/
        $key = $filePath;


        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        $uploadMgr->putFile($token, $key, $path);

        //删除本地图片
        //unlink();
        File::delete($path);
    }
}