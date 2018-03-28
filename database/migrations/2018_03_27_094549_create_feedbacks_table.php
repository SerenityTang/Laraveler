<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->string('type')->comment('意见类型');
            $table->text('description')->comment('意见详情');
            $table->string('url')->nullable()->default('http://www.laraveler.net/')->comment('页面链接');
            $table->string('picture')->nullable()->comment('图片');
            $table->string('contact')->nullable()->comment('联系方式');
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
        Schema::dropIfExists('feedbacks');
    }
}
