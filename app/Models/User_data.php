<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_data extends Model
{
    protected $table = 'user_datas';
    protected $fillable = [
        'user_id',
        'created_at',
        'updated_at'
    ];
}
