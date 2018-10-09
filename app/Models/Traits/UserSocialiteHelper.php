<?php

/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/4/8
 * Time: 下午4:30
 */
namespace App\Models\Traits;

use App\Models\UserSocialite;
use App\User;

trait UserSocialiteHelper
{

    /**
     * 获取绑定$driver用户的本地用户信息
     * @param string $driver 绑定方式[qq、weibo、weixin、weixinweb]
     * @param string $id     第三方用户的openid
     * @return null
     */
    public static function getByDriver($driver, $id)
    {
        $functionMap = [
            'qq'        => 'getByQqId',
            'weibo'     => 'getByWeiboId',
            'weixin'    => 'getByWeixinId',
            'weixinweb' => 'getByWeixinWebId',
            'github'    => 'getByGithubId'
        ];
        $function = $functionMap[$driver];
        if (!$function) {
            return null;
        }

        return self::$function($id);
    }

    /**
     * 获取绑定weibo用户的本地用户信息
     * @param string $id weibo第三方用户的openid
     * @return null
     */
    public static function getByWeiboId($id)
    {
        $OAuth = UserSocialite::where('oauth_type', 'weibo')->where('oauth_id', $id)->first();
        if ($OAuth == null) {
            return null;
        }
        $user = User::where('id', $OAuth->user_id)->first();

        return $user;
    }

    /**
     * 获取绑定qq用户的本地用户信息
     * @param string $id qq第三方用户的openid
     * @return null
     */
    public static function getByQqId($id)
    {
        $OAuth = UserSocialite::where('oauth_type', 'qq')->where('oauth_id', $id)->first();
        if ($OAuth == null) {
            return null;
        }
        $user = User::where('id', $OAuth->user_id)->first();

        return $user;
    }

    /**
     * 获取绑定weixin用户的本地用户信息
     * @param string $id weixin第三方用户的openid
     * @return null
     */
    public static function getByWeixinId($id)
    {
        $OAuth = UserSocialite::where('oauth_type', 'weixin')->where('oauth_id', $id)->first();
        if ($OAuth == null) {
            return null;
        }
        $user = User::where('id', $OAuth->user_id)->first();

        return $user;
    }

    /**
     * 获取绑定微信开放平台用户的本地用户信息
     * @param string $id 微信开放平台用户的openid
     * @return null
     */
    public static function getByWeixinWebId($id)
    {
        $OAuth = UserSocialite::where('oauth_type', 'weixinweb')->where('oauth_id', $id)->first();
        if ($OAuth == null) {
            return null;
        }
        $user = User::where('id', $OAuth->user_id)->first();

        return $user;
    }

    /**
     * 获取绑定微信开放平台用户的本地用户信息
     * @param string $id 微信开放平台用户的openid
     * @return null
     */
    public static function getByGithubId($id)
    {
        $OAuth = UserSocialite::where('oauth_type', 'github')->where('oauth_id', $id)->first();
        if ($OAuth == null) {
            return null;
        }
        $user = User::where('id', $OAuth->user_id)->first();

        return $user;
    }
}