<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreditConfig extends Model
{
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
