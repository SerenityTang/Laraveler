<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    protected $fillable = [
        'user_id',
        'entityable_id',
        'entityable_type',
    ];
}
