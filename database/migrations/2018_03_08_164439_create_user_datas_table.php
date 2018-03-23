<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->integer('coins')->unsigned()->default(0)->comment('金币数');
            $table->integer('credits')->unsigned()->default(0)->comment('积分数');

            $table->integer('question_count')->unsigned()->default(0)->comment('问答数');
            $table->integer('answer_count')->unsigned()->default(0)->comment('回答数');
            $table->integer('article_count')->unsigned()->default(0)->comment('博客数');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数');
            $table->integer('adoption_count')->unsigned()->default(0)->comment('被采纳数');
            $table->integer('support_count')->unsigned()->default(0)->comment('支持数');
            $table->integer('supported_count')->unsigned()->default(0)->comment('被支持数');
            $table->integer('collection_count')->unsigned()->default(0)->comment('收藏数');
            $table->integer('collectioned_count')->unsigned()->default(0)->comment('被收藏数');
            $table->integer('atten_count')->unsigned()->default(0)->comment('关注数');
            $table->integer('attened_count')->unsigned()->default(0)->comment('被关注数');
            $table->integer('attention_count')->unsigned()->default(0)->comment('用户关注数');
            $table->integer('fan_count')->unsigned()->default(0)->comment('粉丝数');
            $table->integer('view_count')->unsigned()->default(0)->comment('主页被访问数');

            $table->dateTime('approval_time')->nullable()->comment('实名认证时间');
            $table->unsignedTinyInteger('approval_status')->nullable()->default(0)->comment('实名认证状态：0->未提交资料，1->待审核，2->审核通过');
            $table->unsignedTinyInteger('email_status')->default(0)->comment('邮箱认证状态：0->未提交资料，1->待审核，2->审核通过');
            $table->unsignedTinyInteger('mobile_status')->default(0)->comment('手机认证状态：0->未提交资料，1->待审核，2->审核通过');
            $table->unsignedTinyInteger('expert_status')->default(0)->comment('达人认证状态：0->未提交资料，1->待审核，2->审核通过');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_datas');
    }
}
