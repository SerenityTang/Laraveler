<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class SupportOpposition extends CoreModel
{
    protected $table = 'support_oppositions';

    protected $fillable = [
        'user_id',
        'sup_opp_able_id',
        'sup_opp_able_type',
        'sup_opp_mode',
        'created_at',
        'updated_at'
    ];
}
