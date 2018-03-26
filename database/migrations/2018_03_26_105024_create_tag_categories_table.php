<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('分类名称');
            $table->index('name');
            $table->longText('description')->nullable()->comment('分类描述');
            $table->integer('weight')->nullable()->default(0)->comment('排序--数值越大，越往后');
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
        Schema::dropIfExists('tag_categories');
    }
}
