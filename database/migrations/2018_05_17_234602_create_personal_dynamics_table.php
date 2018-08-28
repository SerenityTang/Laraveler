<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDynamicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_dynamics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->morphs('source');
            $table->string('action')->comment('操作行为：publishQues,answerQues,voteQues,attentionQues,collectionQues,publishBlog,likeBlog,favoriteBlog');
            $table->string('title')->comment('标题');
            $table->text('content')->nullable()->comment('内容');
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
        Schema::dropIfExists('personal_dynamics');
    }
}
