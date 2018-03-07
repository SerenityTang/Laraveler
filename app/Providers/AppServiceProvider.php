<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //数据库key长度问题
        Schema::defaultStringLength(191);
        //验证码验证规则
        Validator::extend('validateCaptcha', function($attribute, $value, $parameters) {
            return $value == strtolower(session('milkcaptcha'));
        });
        //验证码手机规则
        Validator::extend('validateMobile', function($attribute, $value, $parameters, $validator) {

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
