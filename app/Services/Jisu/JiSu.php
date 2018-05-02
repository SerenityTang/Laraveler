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
        $response = $client->request('POST', 'http://api.jisuapi.com/news/get?channel=' . $channel . '&appkey=' . $this->apiKey);
        $result = json_decode((string) $response->getBody(), true);
        Log::info($result);
        return $result['result']['list'];
    }
}