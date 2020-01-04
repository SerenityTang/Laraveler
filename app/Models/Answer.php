<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use App\Models\Traits\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends CoreModel
{
    protected $table = 'answers';

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
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * 获取问答回答所属的问答
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question', 'question_id', 'id');
    }
}
