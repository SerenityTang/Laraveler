<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class Vote extends CoreModel
{
    protected $table = 'votes';
    protected $fillable = [
        'user_id',
        'entityable_id',
        'entityable_type',
    ];
}
