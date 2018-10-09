<?php

namespace App\Listeners;

use App\Events\AnswerOperationCreditEvent;
use App\Models\UserData;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnswerOperationCreditListener
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
     * @param  AnswerOperationCreditEvent  $event
     * @return void
     */
    public function handle(AnswerOperationCreditEvent $event)
    {
        $user = $event->user;   //回答所属用户
        $type = $event->type;   //操作类型：回答、采纳、支持
        $bool = $event->bool;   //操作取消与否
        $answer_credit_con = UserCreditConfig::where('slug', 'answeredQuestion')->first();  //获取回答问题积分配置记录
        $adopt_credit_con = UserCreditConfig::where('slug', 'adoptedQuestion')->first();  //获取采纳回答积分配置记录
        $support_credit_con = UserCreditConfig::where('slug', 'supportedQuestion')->first();  //获取支持回答积分配置记录
        $UserData = UserData::where('user_id', $user->id)->first();   //获取用户数据记录
        //获取用户当天回答问题流水表记录
        $answer_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $answer_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        //获取用户当天回答被采纳流水表记录
        $adopt_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $adopt_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        //获取用户当天回答被支持流水表记录
        $support_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $support_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        $answer_count = count($answer_credit_sta);    //一天内回答问题加分记录总数
        $adopt_count = count($adopt_credit_sta);    //一天内回答被采纳加分记录总数
        $support_count = count($support_credit_sta);    //一天内回答被支持加分记录总数
        switch ($type) {
            //回答问题
            case 'answer':
                //回答问题加分
                if ($answer_count <= 10) {
                    $data = [
                        'user_id' => $user->id,
                        'type' => $answer_credit_con->slug,
                        'credits' => $answer_credit_con->credits,
                    ];
                    $credit_sta = UserCreditStatement::create($data);
                    if ($credit_sta) {
                        //用户总分添加积分
                        $UserData->credits = $UserData->credits + $answer_credit_con->credits;
                        $UserData->save();
                    }
                }

                break;
            //回答被采纳
            case 'adopt':
                if ($adopt_count <= 5) {
                    $data = [
                        'user_id' => $user->id,
                        'type' => $adopt_credit_con->slug,
                        'credits' => $adopt_credit_con->credits,
                    ];
                    $credit_sta = UserCreditStatement::create($data);
                    if ($credit_sta) {
                        //用户总分添加积分
                        $UserData->credits = $UserData->credits + $adopt_credit_con->credits;
                        $UserData->save();
                    }
                }

                break;
            //回答被支持
            case 'support':
                //回答被支持加分
                if ($bool == 'yes') {
                    if ($support_count <= 5) {
                        $data = [
                            'user_id'   => $user->id,
                            'type'      => $support_credit_con->slug,
                            'credits'   => $support_credit_con->credits,
                        ];
                        $credit_sta = UserCreditStatement::create($data);
                        if ($credit_sta) {
                            //用户总分添加积分
                            $UserData->credits = $UserData->credits + $support_credit_con->credits;
                            $UserData->save();
                        }
                    }
                } else if ($bool == 'no') {
                    //取消回答被支持扣分
                    $data = [
                        'user_id'   => $user->id,
                        'type'      => $support_credit_con->slug,
                        'credits'   => -$support_credit_con->credits,
                    ];
                    $credit_sta = UserCreditStatement::create($data);
                    if ($credit_sta) {
                        //用户总分添加积分
                        $UserData->credits = $UserData->credits - $support_credit_con->credits;
                        $UserData->save();
                    }
                }

                break;
        }
    }
}
