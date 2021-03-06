<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAuthenticatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_authenticates', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->unique()->comment('用户id');
            $table->string('realname')->unique()->comment('真实姓名');
            $table->string('idcard')->unique()->comment('身份证号码');
            $table->string('front_img')->unique()->comment('身份证(正面)');
            $table->string('verso_img')->unique()->comment('身份证(反面)');
            $table->string('hand_img')->unique()->comment('手持身份证(正面)');
            $table->tinyInteger('status')->default(0)->comment('是否通过：：0->否，1->是');
            $table->string('operator_id', 36)->nullable()->comment('审核人id');
            $table->timestampTz('approved_time')->nullable()->comment('审核通过时间');
            $table->longText('feeback')->nullable()->comment('反馈');

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
        Schema::dropIfExists('user_authenticates');
    }
}
