<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/21
 * Time: 下午10:56
 */

//Route::resource('question', 'QuestionController');
Route::group(['prefix' => 'question'], function() {
    //问答tab分类
    Route::get('/{filter?}', ['as' => 'question.index', 'uses' => 'QuestionController@index'])->where(['filter'=>'(newest|hottest|reward|unanswer|unsolve|adopt)']);
    Route::get('/show/{id}', ['as' => 'question.show', 'uses' => 'QuestionController@show']);       //问答内容页
    Route::get('/{id}/show_best_answer', ['as' => 'question.show_best_answer', 'uses' => 'QuestionController@show_best_answer']);       //问答最佳回答
    Route::get('/day_sort', ['as' => 'question.day_sort', 'uses' => 'QuestionController@day_sort']);       //热门问答日榜
    Route::get('/week_sort', ['as' => 'question.week_sort', 'uses' => 'QuestionController@week_sort']);       //热门问答周榜
    Route::get('/month_sort', ['as' => 'question.month_sort', 'uses' => 'QuestionController@month_sort']);       //热门问答月榜
    Route::get('/warm_week', ['as' => 'question.warm_week', 'uses' => 'QuestionController@warm_week']);       //热心周排行榜
    Route::get('/warm_month', ['as' => 'question.warm_month', 'uses' => 'QuestionController@warm_month']);       //热心月排行榜

    Route::group(['middleware' => ['auth']], function() {
        Route::get('/create', ['as' => 'question.create', 'uses' => 'QuestionController@create']);      //创建问答
        Route::any('/store', ['as' => 'question.store', 'uses' => 'QuestionController@store']);      //保存创建问答
        Route::any('/store_draft', ['as' => 'question.store_draft', 'uses' => 'QuestionController@store_draft']);      //保存问答草稿
        Route::get('/show_edit/{id}', ['as' => 'question.show_edit', 'uses' => 'QuestionController@show_edit']);      //修改问答
        Route::post('/edit/{id}', ['as' => 'question.edit', 'uses' => 'QuestionController@edit']);      //保存修改问答
        Route::any('/destroy/{id}', ['as' => 'question.destroy', 'uses' => 'QuestionController@destroy']);      //删除问答
        Route::any('/abandon/{id}', ['as' => 'question.abandon', 'uses' => 'QuestionController@abandon']);      //舍弃问答
        Route::get('/vote/{id}', ['as' => 'question.vote', 'uses' => 'QuestionController@vote']);      //问答投票
        Route::get('/attention/{id}', ['as' => 'question.attention', 'uses' => 'QuestionController@attention']);      //问答关注
        Route::get('/collection/{id}', ['as' => 'question.collection', 'uses' => 'QuestionController@collection']);      //问答收藏
    });
});