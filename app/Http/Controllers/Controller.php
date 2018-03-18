<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResultTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResultTrait;

    /**
     * 操作成功提示
     * @param $url string
     * @param $message 消息内容
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function success($url, $message)
    {
        /*Session::flash('message', $message);
        Session::flash('message_type', 1);
        return redirect($url);*/
        return redirect($url)->with(['message' => $message, 'message_type' => 1]);      //重定向with保存一次性数据到session
    }

    /**
     * 操作失败提示
     * @param $url string
     * @param $message 消息内容
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function error($url, $message)
    {
        Session::flash('message', $message);
        Session::flash('message_type', 2);
        return redirect($url);
    }

}
