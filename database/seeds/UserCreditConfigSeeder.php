<?php

use Illuminate\Database\Seeder;

class UserCreditConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_credit_configs')->delete();

        \App\Models\UserCreditConfig::create([
            'id' =>1,
            'behavior' =>'登录',
            'slug'   => 'login',
            'credits'   => 2,
            'time'   => 1,
            'description'   => '每天登录Laraveler官网',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>2,
            'behavior' =>'签到',
            'slug'   => 'signIn',
            'credits'   => 2,
            'time'   => 1,
            'description'   => '每天Laraveler官网首页签到',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>3,
            'behavior' =>'发布问答',
            'slug'   => 'publishQuestion',
            'credits'   => 3,
            'time'   => 5,
            'description'   => '发布问答到平台',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>4,
            'behavior' =>'问答被投票',
            'slug'   => 'votedQuestion',
            'credits'   => 5,
            'time'   => 5,
            'description'   => '问答被其他用户投票',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>5,
            'behavior' =>'问答被收藏',
            'slug'   => 'collectionedQuestion',
            'credits'   => 5,
            'time'   => 5,
            'description'   => '问答被其他用户收藏',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>6,
            'behavior' =>'回答问题',
            'slug'   => 'answeredQuestion',
            'credits'   => 5,
            'time'   => 10,
            'description'   => '回答平台用户发布的问答',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>7,
            'behavior' =>'回答问题被采纳',
            'slug'   => 'adoptedQuestion',
            'credits'   => 10,
            'time'   => 5,
            'description'   => '回答平台用户发布的问答后被采纳',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>8,
            'behavior' =>'回答问题被支持',
            'slug'   => 'supportedQuestion',
            'credits'   => 5,
            'time'   => 5,
            'description'   => '回答平台用户发布的问答后被支持',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>9,
            'behavior' =>'发布博客',
            'slug'   => 'publishBlog',
            'credits'   => 5,
            'time'   => 5,
            'description'   => '发布博客到平台',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>10,
            'behavior' =>'博客被点赞',
            'slug'   => 'likedBlog',
            'credits'   => 5,
            'time'   => 5,
            'description'   => '博客被其他用户点赞',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>11,
            'behavior' =>'博客被收藏',
            'slug'   => 'collectionedBlog',
            'credits'   => 5,
            'time'   => 5,
            'description'   => '博客被其他用户收藏',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>12,
            'behavior' =>'博客被置顶',
            'slug'   => 'stickiedBlog',
            'credits'   => 10,
            'time'   => 2,
            'description'   => '博客被置顶',
        ]);

        \App\Models\UserCreditConfig::create([
            'id' =>13,
            'behavior' =>'博客被推荐',
            'slug'   => 'promotedBlog',
            'credits'   => 10,
            'time'   => 2,
            'description'   => '博客被推荐',
        ]);
    }
}
