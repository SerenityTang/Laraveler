<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/1/27
 * Time: 下午5:44
 */

Route::any('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);
Route::post('note_verify_code', ['as' => 'auth.note_verify_code', 'uses' => 'Auth\RegisterController@note_verify_code']);
/*忘记密码*/
Route::get('/user/forgot', ['as' => 'auth.user.forgot', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);                    //返回重置密码表单
Route::post('mobile_verify_code', ['as' => 'auth.mobile_verify_code', 'uses' => 'Auth\ForgotPasswordController@mobile_verify_code']);       //获取手机验证码前验证与验证码发送
Route::post('forgot_submit', ['as' => 'auth.forgot_submit', 'uses' => 'Auth\ForgotPasswordController@forgot_submit']);                      //提交重置密码信息
Route::post('reset_submit', ['as' => 'auth.reset_submit', 'uses' => 'Auth\ForgotPasswordController@reset_submit']);                         //提交新密码