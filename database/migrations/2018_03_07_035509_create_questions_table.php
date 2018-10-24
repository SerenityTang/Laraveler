<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->comment('问题发起人id');
            $table->string('qcategory_id', 36)->comment('问题分类id');
            $table->string('title')->comment('问题标题');
            $table->text('description')->nullable()->comment('问题详情');
            $table->smallInteger('price')->default(0)->comment('问题价格');
            //$table->tinyInteger('hide')->default(0)->comment('匿名提问');
            $table->integer('view_count')->unsigned()->default(0)->comment('查看数');
            $table->integer('answer_count')->unsigned()->default(0)->comment('回答数');
            $table->integer('vote_count')->unsigned()->default(0)->comment('投票数');
            $table->integer('attention_count')->unsigned()->default(0)->comment('关注数');
            $table->integer('collection_count')->unsigned()->default(0)->comment('收藏数');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数');
            $table->tinyInteger('device')->default(1)->comment('提问设备类型：1->pc,2->安卓,3->IOS,4->weixin');
            $table->tinyInteger('question_status')->default(0)->comment('问题状态：0->待回答，1->已回答，2->已采纳');
            $table->tinyInteger('status')->default(1)->comment('显示状态：0->否，1->是，2->草稿');

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
        Schema::dropIfExists('questions');
    }
}
