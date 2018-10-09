<?php

namespace App\Listeners;

use App\Events\LoginCreditEvent;
use App\Models\UserData;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginCreditListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginCreditEvent  $event
     * @return void
     */
    public function handle(LoginCreditEvent $event)
    {
        $user = $event->user;
        $credit_con = UserCreditConfig::where('slug', 'login')->first();  //获取登录积分配置记录
        $UserData = UserData::where('user_id', $user->id)->first();   //获取用户数据记录
        //获取用户当天流水表记录
        $credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->first();
        //积分流水表不存在登录积分记录，则说明今天第一次登录
        if (!$credit_sta) {
            $data = [
                'user_id'   => $user->id,
                'type'      => $credit_con->slug,
                'credits'   => $credit_con->credits,
            ];
            $credit_sta = UserCreditStatement::create($data);
            if ($credit_sta) {
                //用户总分添加积分
                $UserData->credits = $UserData->credits + $credit_con->credits;
                $UserData->save();
            }
        }
    }
}
