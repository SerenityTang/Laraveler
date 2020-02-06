<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/4/7
 * Time: 下午12:12
 */

Route::get('/auth/oauth/{driver}', ['as' => 'auth.oauth', 'uses' => 'Auth\LoginController@oauth']);                             //重定向到第三方登录授权页
Route::get('/auth/callback/{driver}', ['as' => 'auth.callback', 'uses' => 'Auth\LoginController@callback']);                    //第三方回调
Route::post('/auth/callback/bind_verify', ['as' => 'auth.callback.bind_verify', 'uses' => 'Auth\OAuthController@bindVerify']);                   //callback页面ajax验证
Route::any('/auth/callback/send', ['as' => 'auth.callback.send', 'uses' => 'Auth\OAuthController@sendMobileCode']);        //callback页面获取验证码
Route::post('/auth/callback/bind', ['as' => 'auth.callback.bind', 'uses' => 'Auth\OAuthController@bind']);                                        //绑定第三方账号