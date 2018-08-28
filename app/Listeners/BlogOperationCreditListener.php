<?php

namespace App\Listeners;

use App\Events\BlogOperationCreditEvent;
use App\Models\User_data;
use App\Models\UserCreditConfig;
use App\Models\UserCreditStatement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BlogOperationCreditListener
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
     * @param  BlogOperationCreditEvent  $event
     * @return void
     */
    public function handle(BlogOperationCreditEvent $event)
    {
        $user = $event->user;   //博客所属用户
        $type = $event->type;   //操作类型：点赞、收藏、置顶、推荐
        $bool = $event->bool;   //操作取消与否
        $like_credit_con = UserCreditConfig::where('slug', 'likedBlog')->first();  //获取点赞博客积分配置记录
        $coll_credit_con = UserCreditConfig::where('slug', 'collectionedBlog')->first();  //获取收藏博客积分配置记录
        $user_data = User_data::where('user_id', $user->id)->first();   //获取用户数据记录
        //获取用户当天点赞流水表记录
        $like_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $like_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        //获取用户当天收藏流水表记录
        $coll_credit_sta = UserCreditStatement::where('user_id', $user->id)->where('type', $coll_credit_con->slug)->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString())->get();
        $like_count = count($like_credit_sta);    //一天内点赞加分记录总数
        $coll_count = count($coll_credit_sta);    //一天内收藏加分记录总数
        switch ($type) {
            //点赞
            case 'like':
                //点赞加分
                if ($bool == 'yes') {
                    if ($like_count <= 5) {
                        $data = [
                            'user_id'   => $user->id,
                            'type'      => $like_credit_con->slug,
                            'credits'   => $like_credit_con->credits,
                        ];
                        $credit_sta = UserCreditStatement::create($data);
                        if ($credit_sta) {
                            //用户总分添加积分
                            $user_data->credits = $user_data->credits + $like_credit_con->credits;
                            $user_data->save();
                        }
                    }
                } else if ($bool == 'no') {
                    //取消点赞扣分
                    $data = [
                        'user_id'   => $user->id,
                        'type'      => $like_credit_con->slug,
                        'credits'   => -$like_credit_con->credits,
                    ];
                    $credit_sta = UserCreditStatement::create($data);
                    if ($credit_sta) {
                        //用户总分添加积分
                        $user_data->credits = $user_data->credits - $like_credit_con->credits;
                        $user_data->save();
                    }
                }

                break;
            //收藏
            case 'favorite':
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
