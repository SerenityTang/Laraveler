<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Collection extends Pivot
{
    protected $table = 'collections';

    protected $fillable = [
        'user_id',
        'entityable_id',
        'entityable_type',
    ];
}
