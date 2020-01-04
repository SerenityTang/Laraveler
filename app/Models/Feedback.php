<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class Feedback extends CoreModel
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
