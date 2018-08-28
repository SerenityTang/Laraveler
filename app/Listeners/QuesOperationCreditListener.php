<?php

namespace App\Listeners;

use App\Events\QuesOperationCreditEvent;
use App\Models\User_data;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuesOperationCreditListener
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
     * @param  QuesOperationCreditEvent  $event
     * @return void
     */
    public function handle(QuesOperationCreditEvent $event)
    {
        $user = $event->user;   //问答所属用户
        $type = $event->type;   //操作类型：投票、收藏
        $bool = $event->bool;   //操作取消与否
        $vote_credit_con = UserCreditConfig::where('slug', 'votedQuestion')->first();  //获取投票问答积分配置记录
        $coll_credit_con = UserCreditConfig::where('slug', 'collectionedQuestion')->first();  //获取收藏问答积分配置记录
        $user_data = User_data::where('user_id', $user->id)->first();   //获取用户数据记录
        //获取用户当天点赞流水表记录
        $vote_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $vote_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        //获取用户当天收藏流水表记录
        $coll_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $coll_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        $vote_count = count($vote_credit_sta);    //一天内点赞加分记录总数
        $coll_count = count($coll_credit_sta);    //一天内收藏加分记录总数
        switch ($type) {
            //投票
            case 'vote':
                //投票加分
                if ($bool == 'yes') {
                    if ($vote_count <= 5) {
                        $data = [
                            'user_id'   => $user->id,
                            'type'      => $vote_credit_con->slug,
                            'credits'   => $vote_credit_con->credits,
                        ];
                        $credit_sta = UserCreditStatement::create($data);
                        if ($credit_sta) {
                            //用户总分添加积分
                            $user_data->credits = $user_data->credits + $vote_credit_con->credits;
                            $user_data->save();
                        }
                    }
                } else if ($bool == 'no') {
                    //取消投票扣分
                    $data = [
                        'user_id'   => $user->id,
                        'type'      => $vote_credit_con->slug,
                        'credits'   => -$vote_credit_con->credits,
                    ];
                    $credit_sta = UserCreditStatement::create($data);
                    if ($credit_sta) {
                        //用户总分添加积分
                        $user_data->credits = $user_data->credits - $vote_credit_con->credits;
                        $user_data->save();
                    }
                }

                break;
            //收藏
            case 'collection':
                //收藏加分
                if ($bool == 'yes') {
                    if ($coll_count <= 5) {
                        $data = [
                            'user_id'   => $user->id,
                            'type'      => $coll_credit_con->slug,
                            'credits'   => $coll_credit_con->credits,
                        ];
                        $credit_sta = UserCreditStatement::create($data);
                        if ($credit_sta) {
                            //用户总分添加积分
                            $user_data->credits = $user_data->credits + $coll_credit_con->credits;
                            $user_data->save();
                        }
                    }
                } else if ($bool == 'no') {
                    //取消收藏扣分
                    $data = [
                        'user_id'   => $user->id,
                        'type'      => $coll_credit_con->slug,
                        'credits'   => -$coll_credit_con->credits,
                    ];
                    $credit_sta = UserCreditStatement::create($data);
                    if ($credit_sta) {
                        //用户总分扣除积分
                        $user_data->credits = $user_data->credits - $coll_credit_con->credits;
                        $user_data->save();
                    }
                }

                break;
        }
    }
}
