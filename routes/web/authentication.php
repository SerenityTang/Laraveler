<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/4/7
 * Time: 下午12:12
 */

Route::get('/auth/oauth/{driver}', ['as' => 'auth.oauth', 'uses' => 'Auth\LoginController@oauth']);
Route::get('/auth/callback/{driver}', ['as' => 'auth.callback', 'uses' => 'Auth\LoginController@callback']);
Route::post('/auth/bind', ['as' => 'auth.bind', 'uses' => 'Auth\OAuthController@bind']);