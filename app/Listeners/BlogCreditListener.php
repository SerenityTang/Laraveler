<?php

namespace App\Listeners;

use App\Events\BlogCreditEvent;
use App\Models\User_data;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BlogCreditListener
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
     * @param  BlogCreditEvent  $event
     * @return void
     */
    public function handle(BlogCreditEvent $event)
    {
        $user = $event->user;
        $credit_con = UserCreditConfig::where('slug', 'publishBlog')->first();  //获取发布博客积分配置记录
        $user_data = User_data::where('user_id', $user->id)->first();   //获取用户数据记录
        //获取用户当天流水表记录
        $credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        $count = count($credit_sta);    //一天内发布问答加分记录总数

        //如一天内发布博客少于10次则加分
        if ($count <= 10) {
            $data = [
                'user_id'   => $user->id,
                'type'      => $credit_con->slug,
                'credits'   => $credit_con->credits,
            ];
            $credit_sta = UserCreditStatement::create($data);
            if ($credit_sta) {
                //用户总分添加积分
                $user_data->credits = $user_data->credits + $credit_con->credits;
                $user_data->save();
            }
        }
    }
}
