<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_title',
        'question_id',
        'user_id',
        'content',
        'device',
        'status',
    ];

    /**
     * 获取对问答回复对应的用户
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
