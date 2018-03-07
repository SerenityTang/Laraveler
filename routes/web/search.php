<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/4
 * Time: 下午5:44
 */

Route::post('/search_tip', ['as' => 'search.tip', 'uses' => 'SearchController@search_tip']);