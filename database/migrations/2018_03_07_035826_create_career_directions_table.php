<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareerDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_directions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 50)->unique()->comment('机器名称');
            $table->string('name')->comment('名称');
            $table->index('name');
            $table->longText('description')->nullable()->comment('概述');
            $table->tinyInteger('status')->default(1)->comment('职业方向状态：0->禁用，1->正常');
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
        Schema::dropIfExists('career_directions');
    }
}
