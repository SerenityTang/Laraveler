<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeixinController extends Controller
{
    public function api()
    {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    //检查微信加密签名
    private function checkSignature()
    {
        /**
         *
         * 微信get请求到填写的服务器地址URL上，携带signature（结合了开发者填写的token参数和请求中的timestamp参数、nonce参数）、timestamp、nonce、echostr参数，
         * 所以先用全局_GET方法获取
         *
         * */
        //1.获取请求中参数
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = "laraveler";

        //2.将timestamp、nonce、token按字典序排序
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);

        //3.将排序后的三个参数字符串拼接成一个字符串进行sha1加密
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        //4.将加密后的字符串与signature进行对比，标识该请求来源于微信
        if ($tmpStr == $signature) {
            return true;

        } else {
            return false;
        }
    }

}
