<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';
    protected $fillable = [
        'user_id',
        'entityable_id',
        'entityable_type',
    ];
}
