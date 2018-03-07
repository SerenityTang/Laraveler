<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'entity_id',
        'entity_type',
        'to_user_id',
        'status',
    ];

    /**
     * 获取评论所属用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * 获取评论被回复用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toUser()
    {
        return $this->belongsTo('App\User', 'to_user_id');
    }
}
