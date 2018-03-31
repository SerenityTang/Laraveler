<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/3/2
 * Time: 下午6:28
 */

Route::group(['prefix' => 'comment'], function() {
    Route::get('/answer_comment/{entity_id}/{entity_type}', ['as' => 'comment.answer_comment.entity_id.entity_type', 'uses' => 'CommentController@answer_comment_show']);
    Route::get('/{entity_id}/{entity_type}/blog_comment', ['as' => 'comment.entity_id.entity_type.blog_comment', 'uses' => 'CommentController@/blog_comment_show']);

    Route::group(['middleware' => ['auth']], function() {
        Route::any('/answer_store', ['as' => 'comment.answer_store', 'uses' => 'CommentController@answer_store']);
        Route::any('/blog_store', ['as' => 'comment.blog_store', 'uses' => 'CommentController@blog_store']);
        Route::any('/mutual_blog_store', ['as' => 'comment.mutual_blog_store', 'uses' => 'CommentController@mutual_blog_store']);
    });
});