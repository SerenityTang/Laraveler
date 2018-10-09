<?php

namespace App\Listeners;

use App\Events\QuestionCreditEvent;
use App\Models\UserData;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuestionCreditListener
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
     * @param  QuestionCreditEvent  $event
     * @return void
     */
    public function handle(QuestionCreditEvent $event)
    {
        $user = $event->user;
        $credit_con = UserCreditConfig::where('slug', 'publishQuestion')->first();  //获取发布问答积分配置记录
        $UserData = UserData::where('user_id', $user->id)->first();   //获取用户数据记录
        //获取用户当天流水表记录
        $credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        $count = count($credit_sta);    //一天内发布问答加分记录总数

        //如一天内发布问答少于10次则加分
        if ($count <= 10) {
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
