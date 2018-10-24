<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;

class UserActivation extends CoreModel
{
    protected $table = 'user_activations';
    protected $fillable = ['user_id', 'token', 'expire_at', 'url'];
}
