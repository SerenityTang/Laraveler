<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/4
 * Time: 下午6:08
 */
//Route::resource('blog', 'BlogController');
Route::group(['prefix' => 'blog'], function() {
    Route::get('/{filter?}', ['as' => 'blog.index', 'uses' => 'BlogController@index'])->where(['filter'=>'(newest|hottest)']);

    Route::group(['middleware' => ['auth']], function() {
        Route::get('/create', ['as' => 'blog.create', 'uses' => 'BlogController@create']);
        Route::post('/store', ['as' => 'blog.store', 'uses' => 'BlogController@store']);
    });
});


Route::any('/mail', ['as' => 'mail', 'uses' => 'BlogController@mail']);