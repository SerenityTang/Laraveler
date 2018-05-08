<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/5/8
 * Time: 下午1:39
 */
Route::group(['prefix' => 'workshow'], function() {
    Route::get('/', ['as' => 'workshow', 'uses' => 'WorkshowController@index']);
});