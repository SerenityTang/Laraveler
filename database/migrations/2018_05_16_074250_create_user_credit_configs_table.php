<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credit_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('behavior')->comment('行为');
            $table->string('slug')->comment('机器码');
            $table->integer('credits')->comment('积分');
            $table->integer('time')->comment('次数(一天内)');
            $table->longText('description')->comment('说明');
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
        Schema::dropIfExists('user_credit_configs');
    }
}
