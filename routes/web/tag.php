<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/8
 * Time: 下午1:25
 */
Route::group(['prefix' => 'tag'], function () {
    Route::get('/', ['as' => 'tag', 'uses' => 'TagController@index']);
    Route::get('/tag_show/{tag_id}', ['as' => 'tag.tag_show', 'uses' => 'TagController@tag_show']);
    Route::post('/attention/{tag_id}', ['as' => 'tag.attention', 'uses' => 'TagController@attention']);         //关注标签
});