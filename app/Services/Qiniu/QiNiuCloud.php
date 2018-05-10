<?php

/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/9
 * Time: 下午9:48
 */
namespace App\Services\Qiniu;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiuCloud
{
    function qiniu_upload($filePath) {


        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = config('filesystems.disks.qiniu.access_key');
        $secretKey = config('filesystems.disks.qiniu.secret_key');
        $bucket = config('filesystems.disks.qiniu.bucket');

        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        // $filePath = './php-logo.png';

        // 上传到七牛后保存的文件名
        $key = basename($filePath);

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        $uploadMgr->putFile($token, $key, $filePath);
        //删除本地图片
        unlink($filePath);
    }
}