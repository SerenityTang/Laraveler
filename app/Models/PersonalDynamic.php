<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDynamic extends Model
{
    protected $table = 'personal_dynamics';
    protected $fillable = [
        'user_id',
        'source_id',
        'source_type',
        'action',
        'title',
        'content',
    ];
}
