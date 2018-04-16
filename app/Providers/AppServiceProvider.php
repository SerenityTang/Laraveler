<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
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
        //验证码验证规则 $value：表单输入的值
        Validator::extend('validateCaptcha', function($attribute, $value) {
            return $value == strtolower(session('milkcaptcha'));
        });
        //验证码手机规则
        Validator::extend('validateMobile', function($attribute, $value, $parameters) {
            //通过传过来手机号参数获取验证码缓存
            $mobile_data = Cache::get($parameters[0]);
            if ($mobile_data) {
                //如通过手机号获取到缓存数据，再验证验证码输入，匹配此用户的短信验证码
                return $value == $mobile_data;
            } else {
                return false;
            }
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
