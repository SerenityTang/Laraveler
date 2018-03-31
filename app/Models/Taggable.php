<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    protected $table = 'taggables';
    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type',
        'created_at',
        'updated_at',
    ];
}
