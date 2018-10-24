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
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->comment('用户id');
            $table->integer('coins')->unsigned()->default(0)->comment('L币数');
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

            $table->timestamps();
            $table->softDeletes();
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
