<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class UserAuthenticate extends CoreModel
{
    protected $table = 'user_authenticates';

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
