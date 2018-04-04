<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/24
 * Time: 下午4:26
 */

Route::group(['prefix' => 'image'], function() {
    Route::get('/show/{image_name}', ['as' => 'image.show', 'uses' => 'ImageController@show']);
    Route::get('/avatar/{avatar_name}',['as'=>'image.avatar','uses'=>'ImageController@avatar'])->where(['avatar_name'=>'[0-9]+_(small|medium|middle|big|origin).jpg']);

    Route::group(['middleware' => ['auth']], function() {
        Route::post('/upload', ['as' => 'image.upload', 'uses' => 'ImageController@upload']);
    });
});
