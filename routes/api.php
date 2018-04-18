<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'weixin'], function() {
    Route::any('/token', ['as' => 'weixin.token', 'uses' => 'Api\WeixinController@token']);
    Route::any('/api', ['as' => 'weixin.api', 'uses' => 'Api\WeixinController@api']);
});
