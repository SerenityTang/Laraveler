<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportOppositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_oppositions', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->comment('用户id');
            $table->string('sup_opp_able_id', 36);
            $table->string('sup_opp_able_type');
            $table->string('sup_opp_mode')->comment('支持或反对类型：支持->support，反对->opposition');

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
        Schema::dropIfExists('support_oppositions');
    }
}
