<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/24
 * Time: 下午4:40
 */

return [
    'keywords' => env('KEYWORDS', 'Laraveler, php, Laravel, php问答, Laravel问答, Laraveler问答, php博客, Laravel博客, Laraveler博客'),
    'description' => env('DESC', 'Laraveler技术问答交流社区平台聚集了优秀的Laravel开发者，致力于为国内广大Laravel程序员打造一个互助、交流、分享的开源社区'),
    'author' => env('AUTHOR', 'Serenity_Tang'),
    'title' => env('TITLE', 'Laraveler - 中文领域的Laravel技术问答交流社区'),
    /*
    |--------------------------------------------------------------------------
    | Upload folder
    |--------------------------------------------------------------------------
    |
    | This defines the site author.
    |
    | Default to 'uploads'.
    |
    */
    'upload_folder' => env('UPLOAD_FOLDER', 'uploads'),

    /*
    |--------------------------------------------------------------------------
    | Upload Max Size
    |--------------------------------------------------------------------------
    |
    | This max size of the upload file.
    |
    | Default to '2000000'.
    |
    */
    'upload' =>[
        'image'=>[
            'max_size' => env('MAX_SIZE', '2048'), //图片上传大小 单位是kb
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Aliyun OSS 设置
    |--------------------------------------------------------------------------
    |
    | This max size of the upload file.
    |
    | Default to '2000000'.
    |
    */
    'aliyun_oss' => env('ALIYUN_OSS', true), //是否开启上传到oss
    'aliyun_oss_bucket' => env('ALIYUN_OSS_BUCKET', 'duxingshe'),
    /*'aliyun_oss_city' => env('ALIYUN_OSS_CITY', '深圳'),
    'aliyun_oss_network_type' => env('ALIYUN_OSS_NETWORK_TYPE', '经典网络'),
    'aliyun_oss_access_key_id' => env('ACCESS_KEY_ID', 'LTAINBJcAJIBo32F'),
    'aliyun_oss_access_key_secret' => env('ACCESS_KEY_SECRET', 'CbmCoEgVGslWve5cvaOX9uB0123k6E'),*/
];