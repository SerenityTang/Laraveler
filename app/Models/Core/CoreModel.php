<?php

namespace App\Models\Core;

use App\Models\Traits\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreModel extends Model
{
    use UuidModelTrait;
    use SoftDeletes;

    //public $incrementing = false;
}
