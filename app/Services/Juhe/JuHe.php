<?php

/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/2
 * Time: 下午11:24
 */

namespace App\Services\Juhe;

use GuzzleHttp\Client;
use Log;

class JuHe
{
    private $apiKey = 'c7ce7d943910e49c79a9edbdd4261a7d';

    //球队对战赛赛程查询
    public function nba($play1, $play2)
    {
        $client = new Client();
        $response = $client->request('POST', 'http://op.juhe.cn/onebox/basketball/combat?key=' . $this->apiKey . '&hteam=' . $play1 . '&vteam=' . $play2);
        $result = json_decode((string)$response->getBody(), true);
        Log::info($result);
        return $result['result'];
    }
}