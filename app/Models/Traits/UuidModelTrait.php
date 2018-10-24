<?php
/**
 * Created by PhpStorm.
 * User: Serenity_Tang
 * Date: 2018/10/24
 * Time: 下午4:13
 */

namespace App\Models\Traits;

use App\Services\Uuid\UUID;

trait UuidModelTrait
{
    public function getIncrementing()
    {
        return false;
    }

    public static function bootUuidModelTrait()
    {
        static::creating(function ($model) {
            if (!is_null($model->getKeyName()) && !isset($model->attributes[$model->getKeyName()])) {
                $model->incrementing = false;
                $id = UUID::id();
                $model->attributes[$model->getKeyName()] = $id;
            }
        }, 0);
    }
}