<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create(config('admin.database.users_table'), function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.roles_table'), function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.permissions_table'), function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.menu_table'), function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();
            $table->string('parent_id', 36)->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri', 50)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.role_users_table'), function (Blueprint $table) {
            $table->string('role_id',36);
            $table->string('user_id',36);
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.role_permissions_table'), function (Blueprint $table) {
            $table->string('role_id',36);
            $table->string('permission_id',36);
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.user_permissions_table'), function (Blueprint $table) {
            $table->string('user_id',36);
            $table->string('permission_id',36);
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.role_menu_table'), function (Blueprint $table) {
            $table->string('role_id',36);
            $table->string('menu_id',36);
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection($connection)->create(config('admin.database.operation_log_table'), function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();
            $table->string('user_id',36);
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip', 15);
            $table->text('input');
            $table->index('user_id');
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
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->dropIfExists(config('admin.database.users_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.roles_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.permissions_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.menu_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.user_permissions_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.role_users_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.role_permissions_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.role_menu_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.operation_log_table'));
    }
}
