<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support_opposition extends Model
{
    protected $fillable = [
        'user_id',
        'sup_opp_able_id',
        'sup_opp_able_type',
        'sup_opp_mode',
        'created_at',
        'updated_at'
    ];
}
