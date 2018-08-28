<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreditStatement extends Model
{
    protected $fillable= [
        'user_id',
        'type',
        'credits',
    ];
}
