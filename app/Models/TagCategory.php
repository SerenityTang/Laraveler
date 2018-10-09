<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagCategory extends Model
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
