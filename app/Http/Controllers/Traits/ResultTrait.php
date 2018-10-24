<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/1/27
 * Time: 下午7:38
 */

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Arr;
use Response;

trait ResultTrait
{
    /**
     * json 格式返回结果
     * @param  int $code 状态码
     * @param  string $msg 消息内容
     * @param  array $data 消息数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResult($code, $message = null, $data = null)
    {
        $message = empty($message) ? config('errors.' . $code) : $message;
        $resultCon = ['code' => $code, 'message' => $message];
        if (empty($data) === false) {
            $resultCon['data'] = $data;
        }

        return response()->json($resultCon);
    }
}