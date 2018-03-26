<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag_Category extends Model
{
    protected $table = 'tag_categories';
    protected $fillable = [
        'name',
        'description',
        'weight',
        'created_at',
        'updated_at'
    ];
}
