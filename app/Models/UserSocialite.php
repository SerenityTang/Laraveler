<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class UserSocialite extends CoreModel
{
    protected $table = 'user_socialites';
    protected $fillable = [
        'user_id',
        'oauth_type',
        'oauth_id',
        'oauth_access_token',
        'oauth_expires',
        'nickname',
        'avatar',
        'created_at',
        'updated_at',
    ];

    /**
     * 获取对应的用户
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
