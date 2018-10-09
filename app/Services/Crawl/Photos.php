<?php

/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/6
 * Time: 上午10:26
 */

namespace App\Services\Crawl;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use PHPHtmlParser\Dom;

class Photos
{
    private $base_url = 'http://www.duxingshe.cn/photos';
    private $downloadDir = 'dxs';

    public function crawlDxs()
    {
        $dom = new Dom();
        $jar = new CookieJar();
        $client = new Client(['base_uri' => $this->base_url, 'cookies' => true]);
        $response = $client->request('GET', '', ['cookies' => $jar]);

        /*$client = new Client();
        $response = $client->request('GET', $this->base_url);*/

        $dom->load((string)$response->getBody());//dd($dom->load((string) $response->getBody()));

        $photo = $dom->find('.photo_thumbnail');
        $count = count($photo);     //获取首页图片总数
        $num = random_int(1, $count);   //在1-总数之间生成随机整数
        $dom->loadStr($photo[$num]->innerHtml, []);
        $img_url = $dom->find('img')->getAttribute('src');

        if (!is_dir(storage_path('/app/' . $this->downloadDir))) {
            mkdir(storage_path('/app/' . $this->downloadDir));
            chmod(storage_path('/app/' . $this->downloadDir), 0777);
        }
        $filename = uniqid() . '.' . pathinfo(explode('?', $img_url)[0])['extension'];
        $path = storage_path('/app/' . $this->downloadDir) . '/' . $filename;
        $resource = fopen($path, 'w');
        $stream = \GuzzleHttp\Psr7\stream_for($resource);
        $client->request('GET', $img_url, [
            'save_to' => $stream,
            'headers' => [
                'Referer' => 'http://www.duxingshe.cn/photos'
            ]
        ]);

        return $path;
    }
}