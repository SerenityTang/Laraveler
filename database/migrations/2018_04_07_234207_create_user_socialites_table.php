<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_socialites', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('user_id', 36)->default(0)->comment('用户id');
            $table->string('oauth_type', 50)->comment('oauth 类型');
            $table->string('oauth_id', 150)->unique()->comment('oauth ID');
            $table->string('oauth_access_token', 150)->nullable()->unique()->comment('oauth access token');
            $table->string('oauth_expires', 150)->nullable()->comment('oauth expires');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');

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
        Schema::dropIfExists('user_socialites');
    }
}
