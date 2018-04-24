<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'url',
        'picture',
        'contact',
        'created_at',
        'updated_at'
    ];
}
