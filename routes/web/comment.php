<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/3/2
 * Time: 下午6:28
 */

Route::group(['prefix' => 'comment'], function() {
    Route::get('/{entity_id}/{entity_type}', ['as' => 'comment.entity_id.entity_type', 'uses' => 'CommentController@show']);

    Route::group(['middleware' => ['auth']], function() {
        Route::any('/store', ['as' => 'comment.store', 'uses' => 'CommentController@store']);

    });
});