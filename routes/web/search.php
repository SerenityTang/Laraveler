<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/4
 * Time: 下午5:44
 */

Route::group(['prefix' => 'search'], function() {
    Route::get('/{filter?}', ['as' => 'search.show', 'uses' => 'SearchController@search_scout'])->where(['filter'=>'(all|question|blog|tag)']);
    Route::post('/', ['as' => 'search.search_scout', 'uses' => 'SearchController@search_scout']);
});
Route::group(['prefix' => 'search', 'middleware' => ['auth']], function() {
    Route::any('/tags/{QUERY}', ['as' => 'search.tags', 'uses' => 'SearchController@tags']);
});
