<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('comments', function(Blueprint $table) {
      // These columns are needed for Baum's Nested Set implementation to work.
      // Column names may be changed, but they *must* all exist and be modified
      // in the model.
      // Take a look at the model scaffold comments for details.
      // We add indexes on parent_id, lft, rgt columns by default.
      $table->increments('id');

      $table->integer('user_id')->unsigned()->comment('评论人');
      $table->index('user_id');
      $table->text('content')->comment('评论内容');
      $table->integer('commentable_id')->unsigned()->comment('实体id');
      $table->index('commentable_id');
      $table->string('commentable_type')->comment('实体类型：Answer,Question,Blog');
      $table->index('commentable_type');
      $table->integer('to_user_id')->unsigned()->nullable()->comment('对回复的评论进行再回复');
      $table->integer('support_count')->unsigned()->default(0)->comment('支持数');
      $table->tinyInteger('status')->default(1)->comment('显示状态：0->否，1->是');

      $table->integer('parent_id')->nullable()->index();
      $table->integer('lft')->nullable()->index();
      $table->integer('rgt')->nullable()->index();
      $table->integer('depth')->nullable();

      // Add needed columns here (f.ex: name, slug, path, etc.)
      // $table->string('name', 255);
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('comments');
  }

}
