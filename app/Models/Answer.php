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
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * 获取问答回答所属的问答
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question', 'question_id', 'id');
    }
}
