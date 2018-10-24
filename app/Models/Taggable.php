<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class Taggable extends CoreModel
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
