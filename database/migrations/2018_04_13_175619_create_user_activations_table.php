<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->string('token')->unique()->comment('激活密钥');
            $table->boolean('active')->default(1)->comment('激活密钥状态：0->过期，1->正常');
            $table->timestamps();

            /*$table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activations');
    }
}
