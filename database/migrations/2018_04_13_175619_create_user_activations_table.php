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
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->comment('用户id');
            $table->string('token')->unique()->comment('激活密钥');
            $table->boolean('active_status')->default(1)->comment('激活密钥状态：0->过期，1->正常');
            $table->string('url')->comment('邮箱激活链接');
            $table->timestampTz('active_at')->nullable()->comment('密钥激活时间');
            $table->timestampTz('expire_at')->comment('密钥过期时间');

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
        Schema::dropIfExists('user_activations');
    }
}
