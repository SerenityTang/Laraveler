<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/10
 * Time: 下午3:44
 */


Route::group(['prefix' => 'user', 'middleware' => ['auth']], function() {
    /*个人主页*/
    Route::get('{username}', ['as' => 'user.index', 'uses' => 'UserController@index']);
    Route::get('{username}/question', ['as' => 'user.question', 'uses' => 'UserController@question']);
    Route::get('{username}/replies', ['as' => 'user.replies', 'uses' => 'UserController@replies']);
    Route::get('{username}/articles', ['as' => 'user.articles', 'uses' => 'UserController@articles']);
    Route::get('{username}/following', ['as' => 'user.following', 'uses' => 'UserController@following']);
    Route::get('{username}/fans', ['as' => 'user.fans', 'uses' => 'UserController@fans']);
    Route::get('{username}/likes', ['as' => 'user.likes', 'uses' => 'UserController@likes']);
    Route::get('{username}/favorites', ['as' => 'user.favorites', 'uses' => 'UserController@favorites']);

    /*个人设置*/
    Route::get('{username}/settings', ['as' => 'user.settings', 'uses' => 'UserController@settings']);
    Route::get('{username}/authenticate', ['as' => 'user.authenticate', 'uses' => 'UserController@authenticate']);
    Route::get('{username}/edit_password', ['as' => 'user.edit_password', 'uses' => 'UserController@edit_password']);
    Route::get('{username}/edit_notify', ['as' => 'user.edit_notify', 'uses' => 'UserController@edit_notify']);
    Route::get('{username}/security', ['as' => 'user.security', 'uses' => 'UserController@security']);
    Route::get('{username}/bindsns', ['as' => 'user.bindsns', 'uses' => 'UserController@bindsns']);
    Route::get('{username}/career_status', ['as' => 'user.career_status', 'uses' => 'UserController@career_status']);

    /*用户个人相关信息修改*/
    Route::post('auth/profile/per_detail', ['as' => 'user.auth.profile.per_detail', 'uses' => 'UserController@per_detail']);    //修改个人信息
    Route::post('auth/profile/avatar', ['as' => 'user.auth.profile.avatar', 'uses' => 'UserController@post_avatar']);   //修改头像
    Route::post('profile/modify_password', ['as' => 'user.profile.modify_password', 'uses' => 'UserController@modify_password']);   //修改密码
});


