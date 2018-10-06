<?php
/**
 * Created by PhpStorm.
 * User: Serenity_Tang
 * Date: 2018/10/5
 * Time: 上午9:58
 */

namespace App\Types;


class BlogType
{
    public static $newest = 1;      //最新博客
    public static $hottest = 2;     //热门博客

    public static function getArray()
    {
        return [
            1 => "最新博客",
            2 => "热门博客",
        ];
    }


    /**
     * 通过数字获取博客类型字符
     * @param $status
     * @return mixed
     */
    public static function getStatus($status)
    {
        foreach (QuestionType::getArray() as $item => $value) {
            if ($item == $status) {
                return $value;
                break;
            }
        }
    }

    /**
     * 通过博客类型字符获取数字
     *
     * @param $text
     * @return int|string
     */
    public static function getStatusText($text)
    {
        foreach (QuestionType::getArray() as $item => $value) {
            if ($value == $text) {
                return $item;
                break;
            }
        }
    }
}