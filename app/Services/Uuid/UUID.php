<?php
/**
 * Created by PhpStorm.
 * User: Serenity_Tang
 * Date: 2018/10/24
 * Time: 下午5:34
 */

namespace App\Services\Uuid;

use Ramsey\Uuid\Uuid as u;

class UUID
{
    public function __construct()
    {

    }

    public static function id()
    {
        $data = u::uuid4();
        $str = $data->getBytes();
        $str = base64_encode($str);
        $str = str_replace("+", "_", $str);
        $str = str_replace("/", "_", $str);
        return $str;
    }

    public static function longId()
    {
        $data = u::uuid4();
        $str = $data->toString();
        $str = str_replace("-", "", $str);
        return $str;
    }
}