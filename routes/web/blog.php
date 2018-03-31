<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/4
 * Time: 下午6:08
 */
//Route::resource('blog', 'BlogController');
Route::group(['prefix' => 'blog'], function() {
    //博客tab分类
    Route::get('/{filter?}', ['as' => 'blog.index', 'uses' => 'BlogController@index'])->where(['filter'=>'(newest|hottest)']);
    Route::get('/show/{id}', ['as' => 'blog.show', 'uses' => 'BlogController@show']);       //博客内容页
    Route::get('/sort_show/{id}/{sort}', ['as' => 'blog.sort_show', 'uses' => 'BlogController@sort_show']);       //博客内容页

    Route::group(['middleware' => ['auth']], function() {
        Route::get('/create', ['as' => 'blog.create', 'uses' => 'BlogController@create']);      //创建博客
        Route::post('/store', ['as' => 'blog.store', 'uses' => 'BlogController@store']);        //保存发布博客
        Route::post('/store_draft', ['as' => 'blog.store_draft', 'uses' => 'BlogController@store_draft']);        //保存博客草稿
        Route::any('/destroy/{id}', ['as' => 'blog.destroy', 'uses' => 'BlogController@destroy']);      //删除博客
        Route::any('/abandon/{id}', ['as' => 'blog.abandon', 'uses' => 'BlogController@abandon']);      //舍弃博客
    });
});


Route::any('/mail', ['as' => 'mail', 'uses' => 'BlogController@mail']);