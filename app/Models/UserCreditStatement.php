<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class UserCreditStatement extends CoreModel
{
    protected $table = 'user_credit_statements';

    protected $fillable= [
        'user_id',
        'type',
        'credits',
    ];
}
