<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Vote extends Pivot
{
    protected $table = 'votes';
    protected $fillable = [
        'user_id',
        'entityable_id',
        'entityable_type',
    ];
}
