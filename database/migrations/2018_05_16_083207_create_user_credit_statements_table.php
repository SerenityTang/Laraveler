<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credit_statements', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->comment('用户id');
            $table->string('type')->comment('行为');
            $table->integer('credits')->comment('获得积分');

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
        Schema::dropIfExists('user_credit_statements');
    }
}
