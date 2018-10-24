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
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->comment('用户id');
            $table->string('source_id', 36);
            $table->string('source_type');
            $table->string('action')->comment('操作行为：publishQues,answerQues,voteQues,attentionQues,collectionQues,publishBlog,likeBlog,favoriteBlog');
            $table->string('title')->comment('标题');
            $table->text('content')->nullable()->comment('内容');

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
        Schema::dropIfExists('personal_dynamics');
    }
}
