<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class UserCreditConfig extends CoreModel
{
    protected $table = 'user_credit_configs';
    protected $fillable= [
        'id',
        'behavior',
        'slug',
        'credits',
        'time',
        'description',
        'points',
    ];
}
