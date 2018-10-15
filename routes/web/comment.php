<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/3/2
 * Time: 下午6:28
 */

Route::group(['prefix' => 'comment'], function () {
    Route::get('/{entity_id}/{entity_type}', ['as' => 'comment.entity_id.entity_type', 'uses' => 'CommentController@show']);
    Route::get('/{entity_id}/{entity_type}/blog_comment', ['as' => 'comment.entity_id.entity_type.blog_comment', 'uses' => 'CommentController@/blog_comment_show']);

    Route::group(['middleware' => ['auth']], function () {
        Route::any('/answer_store', ['as' => 'comment.answer_store', 'uses' => 'CommentController@answer_store']);                  //问答回复评论
        Route::any('/blog_store', ['as' => 'comment.blog_store', 'uses' => 'CommentController@blog_store']);                        //博客评论
        Route::any('/mutual_blog_store', ['as' => 'comment.mutual_blog_store', 'uses' => 'CommentController@mutual_blog_store']);   //博客相互评论
        Route::any('/support/{comment_id}', ['as' => 'comment.support', 'uses' => 'CommentController@support']);                    //博客评论支持
        Route::any('/edit/{comment_id}', ['as' => 'comment.edit', 'uses' => 'CommentController@edit']);                             //博客评论编辑
        Route::any('/destroy/{comment_id}', ['as' => 'comment.destroy', 'uses' => 'CommentController@destroy']);                    //博客评论删除
    });
});