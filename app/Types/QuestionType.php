<?php
/**
 * Created by PhpStorm.
 * User: Serenity_Tang
 * Date: 2018/10/4
 * Time: 下午10:59
 */

namespace App\Types;


class QuestionType
{
    public static $newest = 1;      //最新问答
    public static $hottest = 2;     //热门问答
    public static $reward = 3;      //悬赏问答
    public static $unanswer = 4;    //零回答
    public static $unsolve = 5;     //待解决
    public static $adopt = 6;       //已采纳

    public static function getArray()
    {
        return [
            1 => "最新问答",
            2 => "热门问答",
            3 => "悬赏问答",
            4 => "零回答",
            5 => "待解决",
            6 => "已采纳",
        ];
    }


    /**
     * 通过数字获取问答类型字符
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
     * 通过问答类型字符获取数字
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