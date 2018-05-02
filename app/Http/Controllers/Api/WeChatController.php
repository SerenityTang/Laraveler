<?php

namespace App\Http\Controllers\Api;

use App\Services\Jisu\JiSu;
use App\Services\Tuling\TuLing;
use EasyWeChat\Kernel\Messages\Article;
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
                    $param = explode(' ', $message['Content']);
                    $jisu = new JiSu();
                    if (in_array($param[0], ['头条','财经','体育','娱乐','军事','教育','科技','NBA','股票','星座','女性','健康','育儿'])) {
                        $result = $jisu->news($message['Content']);
                        $results = [];
                        $order = 1;
                        foreach ($result as $res) {
                            array_push($results, '<a href="' . $res['url'] . '">' . $order . '、' . $res['title'] . '</a>');
                            $order++;
                        }
                        return implode("\n\r", $results);
                    } else if (is_numeric($param[1])) {
                        $result = $jisu->bus($param[0], $param[1]);
                        $results = [];
                        $data1 = [
                            '车次' => $result[0]['transitno'],
                            '票价' => $result[0]['price'],
                        ];
                        $data2 = [
                            '始发站' => $result[0]['startstation'],
                            '终点站' => $result[0]['endstation'],
                        ];
                        $data3 = [
                            '早班车' => $result[0]['starttime'],
                            '晚班车' => $result[0]['endtime'],
                        ];
                        array_push($results, $data1, $data2, $data3);
                        foreach ($result[0]['list'] as $res) {
                            array_push($results, $res['sequenceno'] . '.' . $res['station']);
                        }

                        $data4 = [
                            '车次' => $result[1]['transitno'],
                            '票价' => $result[1]['price'],
                        ];
                        $data5 = [
                            '始发站' => $result[1]['startstation'],
                            '终点站' => $result[1]['endstation'],
                        ];
                        $data6 = [
                            '早班车' => $result[1]['starttime'],
                            '晚班车' => $result[1]['endtime'],
                        ];
                        array_push($results, $data4, $data5, $data6);
                        foreach ($result[1]['list'] as $res) {
                            array_push($results, $res['sequenceno'] . '.' . $res['station']);
                        }
                        return implode("\n\r", $results);
                    }
                    $tuling = new TuLing();
                    $res = $tuling->bot($message['Content'], $message['FromUserName']);
                    return $res['values'][$res['resultType']];

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
