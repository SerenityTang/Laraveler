<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuthenticate extends Model
{
    protected $fillable = [
        'user_id',
        'realname',
        'idcard',
        'front_img',
        'verso_img',
        'hand_img',
        'status',
        'operator_id',
        'approved_time',
        'feeback',
    ];
}
