<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/3/2
 * Time: 下午3:01
 */

Route::group(['prefix' => 'answer'], function () {
    Route::get('/sort_show/{id}/{sort}', ['as' => 'answer.sort_show', 'uses' => 'AnswerController@sort_show']);        //问答的回复按条件显示

    Route::group(['middleware' => ['auth']], function () {
        Route::any('/store', ['as' => 'answer.store', 'uses' => 'AnswerController@store']);                             //保存问答的回答
        Route::any('/adopt/{id}', ['as' => 'answer.adopt', 'uses' => 'AnswerController@adopt']);                        //设置问答的回复为最佳
        Route::get('/support/{id}', ['as' => 'answer.support', 'uses' => 'AnswerController@support']);                  //支持问答的回复
        Route::get('/oppose/{id}', ['as' => 'answer.oppose', 'uses' => 'AnswerController@oppose']);                     //反对问答的回复
    });
});