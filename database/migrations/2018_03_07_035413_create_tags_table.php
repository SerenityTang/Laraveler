<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('tcategory_id', 36)->comment('标签分类id');
            $table->string('name')->unique()->comment('标签名称');
            $table->string('logo')->unique()->nullable()->comment('标签图标');
            $table->longText('description')->nullable()->comment('标签描述');
            $table->tinyInteger('status')->default(1)->comment('标签状态：0->否，1->是');
            $table->integer('attention_count')->unsigned()->index()->default(0)->comment('关注数');

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
        Schema::dropIfExists('tags');
    }
}
