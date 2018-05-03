<?php

/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/2
 * Time: 下午12:45
 */
namespace App\Services\Jisu;

use GuzzleHttp\Client;
use Log;

class JiSu
{
    private $apiKey = 'fefda7afffb8d75f';

    //获取新闻
    public function news($channel) {
        $client = new Client();
        $response = $client->request('POST', 'http://api.jisuapi.com/news/get?channel=' . $channel . '&num=8' . '&appkey=' . $this->apiKey);
        $result = json_decode((string) $response->getBody(), true);
        Log::info($result);
        return $result['result']['list'];
    }

    //获取新闻频道
    public function getChannels() {
        $client = new Client();
        $response = $client->request('GET', 'http://api.jisuapi.com/news/channel?appkey=' . $this->apiKey);
        $result = json_decode((string) $response->getBody(), true);
        Log::info($result);
        return $result['result'];
    }

    //公交线路查询
    public function bus($city, $transitno) {
        $client = new Client();
        $response = $client->request('POST', 'http://api.jisuapi.com/transit/line?appkey=' . $this->apiKey . '&city=' . $city . '&transitno=' . $transitno);
        $result = json_decode((string) $response->getBody(), true);
        Log::info($result);
        return $result['result'];
    }
}