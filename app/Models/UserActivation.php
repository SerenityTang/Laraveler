<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivation extends Model
{
    protected $fillable= ['user_id','active','token'];
}
