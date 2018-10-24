<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class TagCategory extends CoreModel
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
