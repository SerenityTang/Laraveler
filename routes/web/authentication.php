<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/4/7
 * Time: 下午12:12
 */

Route::get('/auth/oauth/{driver}', ['as' => 'auth.oauth', 'uses' => 'Auth\LoginController@oauth']);                             //重定向到第三方登录授权页
Route::get('/auth/callback/{driver}', ['as' => 'auth.callback', 'uses' => 'Auth\LoginController@callback']);                    //第三方回调
Route::post('/auth/bind_verify', ['as' => 'auth.bind_verify', 'uses' => 'Auth\OAuthController@bind_verify']);                   //callback页面ajax验证
Route::any('/auth/get_mobile_code', ['as' => 'auth.get_mobile_code', 'uses' => 'Auth\OAuthController@get_mobile_code']);        //callback页面获取验证码
Route::post('/auth/bind', ['as' => 'auth.bind', 'uses' => 'Auth\OAuthController@bind']);                                        //绑定第三方账号