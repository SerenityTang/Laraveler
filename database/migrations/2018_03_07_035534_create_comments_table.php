<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('评论人');
            $table->index('user_id');
            $table->text('content')->comment('评论内容');
            $table->integer('entity_id')->unsigned()->comment('实体id');
            $table->index('entity_id');
            $table->string('entity_type', 50)->comment('实体类型：Answer,Question,Blog');
            $table->index('entity_type');
            $table->integer('to_user_id')->unsigned()->nullable()->comment('对回复的评论进行再回复');
            $table->integer('support_count')->unsigned()->default(0)->comment('支持数');
            $table->tinyInteger('status')->default(1)->comment('显示状态：0->否，1->是');
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
        Schema::dropIfExists('comments');
    }
}
