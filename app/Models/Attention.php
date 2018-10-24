<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class Attention extends CoreModel
{

    protected $fillable = [
        'user_id',
        'entityable_id',
        'entityable_type',
    ];
}
