<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('bcategory_id', 36)->comment('博客分类id');
            $table->string('user_id', 36)->comment('博客作者id');
            $table->string('title')->comment('博客标题');
            $table->string('intro')->nullable()->comment('博客简介');
            $table->longText('description')->comment('博客详情');
            $table->string('cover')->nullable()->comment('博客封面');
            $table->string('music')->nullable()->comment('博客音乐');
            $table->tinyInteger('source')->default(0)->comment('博客来源，1->原创，2->转载，3->翻译');
            $table->string('source_name')->nullable()->comment('博客来源名称');
            $table->string('source_link')->nullable()->comment('博客原文链接');
            $table->tinyInteger('status')->default(1)->comment('显示状态：0->否，1->是，2->草稿');
            $table->integer('view_count')->default(0)->comment('浏览量');
            $table->integer('like_count')->default(0)->comment('点赞数');
            $table->integer('favorite_count')->default(0)->comment('收藏数');
            $table->integer('comment_count')->default(0)->comment('评论数');
            $table->integer('sticky')->default(0)->comment('置顶状态：0->否，1->是');
            $table->integer('promote')->default(0)->comment('推荐状态：0->否，1->是');

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
        Schema::dropIfExists('blogs');
    }
}
