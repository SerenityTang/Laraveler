<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = [
        'tcategory_id',
        'name',
        'logo',
        'description',
        'status',
        'attention_count',
        'created_at',
        'updated_at'
    ];
}
