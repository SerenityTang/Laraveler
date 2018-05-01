<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/1
 * Time: 下午9:10
 */
namespace   App\Services\Tuling;

use GuzzleHttp\Client;
use Log;

class TuLing
{
    private $apiKey = '0eb9fd86269c42a2b974a873597e6283';
    private $apiUrl = 'http://openapi.tuling123.com/openapi/api/v2';

    public function bot($content, $toUserId = 1) {
        $data = [
            'perception' => [
                'inputText' => [
                    'text' => $content,
                ]
            ],
            'userInfo' => [
                'apiKey' => $this->apiKey,
                'userId' => $toUserId,
            ],
        ];
        $client = new Client();
        $response = $client->request('POST', $this->apiUrl, ['json' => $data]);
        $result = json_decode((string)$response->getBody());
        Log::info($result);
        return $result['results'][0];
    }
}