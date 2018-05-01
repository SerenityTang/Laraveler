<?php

namespace App\Http\Controllers\Api;

use App\Services\Tuling\TuLing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            switch ($message['MsgType']) {
                case 'event':
                    return "Hello, welcome to Laraveler-中文领域的Laravel技术问答交流社区官方微信 ^_^ 官方网站：https://www.laraveler.net，欢迎加入O(∩_∩)O";
                    break;
                case 'text':
                    if ($message['Content'] == '机器人') {
                        $tuling = new TuLing();
                        $res = $tuling->bot($message['Content'], $message['FromUserName']);
                        return $res['values'][$res['resultType']];
                    }
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        return $app->server->serve();
    }
}
