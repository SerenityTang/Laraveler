<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('question_title')->comment('问题标题');
            $table->string('question_id',36)->comment('问题id');
            $table->string('user_id',36)->comment('回答发起人id');
            $table->text('content')->comment('回答内容');
            $table->integer('support_count')->unsigned()->default(0)->comment('支持数');
            $table->integer('opposition_count')->unsigned()->default(0)->comment('反对数');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数');
            $table->tinyInteger('device')->default(1)->comment('回答设备类型：1->pc,2->安卓,3->IOS,4->weixin');
            $table->tinyInteger('status')->default(0)->comment('显示状态：0->否，1->是');
            $table->timestampTz('adopted_at')->nullable()->comment('回答采纳时间');

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
        Schema::dropIfExists('answers');
    }
}
