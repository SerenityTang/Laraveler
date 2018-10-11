<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Encore\Admin\Traits\AdminBuilder;
use App\Models\Traits\UserSocialiteHelper;

class User extends Authenticatable
{
    use Notifiable, AdminBuilder, UserSocialiteHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    protected $fillable = [
        'username',
        'email',
        'mobile',
        'password',
        'user_status',
        'personal_domain'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function getAvatarPath($userId, $size = 'big', $ext = 'jpg')
    {
        $avatarDir = self::getAvatarDir($userId);
        $avatarFileName = self::getAvatarFileName($userId, $size);
        return $avatarDir . DIRECTORY_SEPARATOR . $avatarFileName . '.' . $ext;
    }

    /**
     * 获取用户头像存储目录
     * @param $user_id
     * @return string
     */
    public static function getAvatarDir($userId, $rootPath = 'avatar')
    {
        /*$userId = sprintf("%09d", $userId);
        return $rootPath.'/'.substr($userId, 0, 3) . '/' . substr($userId, 3, 2) . '/' . substr($userId, 5, 2);*/
        $rootDir = config('global.upload_folder');
        return $rootDir . DIRECTORY_SEPARATOR . $rootPath . DIRECTORY_SEPARATOR . $userId;
    }


    /**
     * 获取头像文件命名
     * @param string $size
     * @return mixed
     */
    public static function getAvatarFileName($userId, $size = 'big')
    {
        $avatarNames = [
            'small' => 'user_small_' . $userId,
            'medium' => 'user_medium_' . $userId,
            'middle' => 'user_middle_' . $userId,
            'big' => 'user_big_' . $userId,
            'origin' => 'user_origin_' . $userId
        ];
        return $avatarNames[$size];

        //return 'user_' . $userId . '_' . substr(md5(time()), 0, 30);
    }

    /**
     * 获取用户回答
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'user_id', 'id');
    }

    /**
     * 获取用户评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Answer');
    }

    /**
     * 是否回答过此问题
     *
     * @param $question_id
     * @return bool
     */
    public function isAnswer($question_id)
    {
        return boolval($this->answers()->where('question_id', $question_id)->count());
    }

    /**
     * 获取用户问答
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany('App\Models\Question', 'user_id', 'id');
    }

    /**
     * 获取用户博客
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs()
    {
        return $this->hasMany('App\Models\Blog', 'user_id', 'id');
    }

    /**
     * 获取用户关注的问答
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function atte_ques()
    {
        return $this->hasMany('App\Models\Attention', 'user_id', 'id')->where('entityable_type', 'App\Models\Question');
    }

    /**
     * 获取用户关注的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function atte_user()
    {
        return $this->hasMany('App\Models\Attention', 'user_id', 'id')->where('entityable_type', 'App\Models\User');
    }

    /**
     * 获取用户收藏的问答
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coll_ques()
    {
        return $this->hasMany('App\Models\Collection', 'user_id', 'id')->where('entityable_type', 'App\Models\Question');
    }

    /**
     * 获取用户支持的回答
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supp_answer()
    {
        return $this->hasMany('App\Models\SupportOpposition', 'user_id', 'id')->where('sup_opp_able_type', 'App\Models\Answer')->where('sup_opp_mode', 'support');
    }

    /**
     * 获取用户反对的回答
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oppo_answer()
    {
        return $this->hasMany('App\Models\SupportOpposition', 'user_id', 'id')->where('sup_opp_able_type', 'App\Models\Answer')->where('sup_opp_mode', 'opposition');
    }

    /**
     * 获取用户点赞的博客
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function like_blog()
    {
        return $this->hasMany('App\Models\SupportOpposition', 'user_id', 'id')->where('sup_opp_able_type', 'App\Models\Blog')->where('sup_opp_mode', 'like');
    }

    /**
     * 获取用户点赞的博客
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coll_blog()
    {
        return $this->hasMany('App\Models\Collection', 'user_id', 'id')->where('entityable_type', 'App\Models\Blog');
    }

    /**
     * 获取用户邮箱验证的激活信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activations()
    {
        return $this->hasOne(\App\Models\UserActivation::class);
    }

    /**
     * 用户数据信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userData()
    {
        return $this->hasOne(UserData::class, 'user_id', 'id');
    }
}
