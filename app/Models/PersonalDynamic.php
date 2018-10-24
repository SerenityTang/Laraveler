<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class PersonalDynamic extends CoreModel
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
