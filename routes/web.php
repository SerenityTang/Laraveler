<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/web/admin.php';
require __DIR__.'/web/auth.php';
require __DIR__.'/web/search.php';
require __DIR__.'/web/answer.php';
require __DIR__ . '/web/question.php';
require __DIR__.'/web/blog.php';
require __DIR__ . '/web/comment.php';
require __DIR__.'/web/image.php';
require __DIR__.'/web/user.php';
require __DIR__.'/web/authentication.php';
require __DIR__.'/web/tag.php';
require __DIR__.'/web/workshow.php';
require __DIR__.'/api.php';

//框架自带欢迎页
/*Route::get('/', function () {
    return view('welcome');
});*/

//执行认证生成的路由
Auth::routes();

//执行认证生成的首页
Route::get('/', 'HomeController@index')->name('home');

//登录页面验证码
Route::get('/captcha/verify', ['as' => 'captcha.verify', 'uses' => 'CaptchaController@verify']);

//意见反馈
Route::post('/feedback', ['as' => 'feedback', 'uses' => 'HomeController@feedback']);
//关于我们
Route::get('/about', ['as' => 'about', 'uses' => 'HomeController@about']);
//联系我们
Route::get('/contact', ['as' => 'contact', 'uses' => 'HomeController@contact']);
//帮助中心
Route::group(['prefix' => 'help'], function() {
    Route::get('/', ['as' => 'help', 'uses' => 'HomeController@help']);
    Route::get('/credit/introduce', ['as' => 'help.credit.introduce', 'uses' => 'HomeController@credit_introduce']);    //积分介绍
    Route::get('/credit/rule', ['as' => 'help.credit.rule', 'uses' => 'HomeController@credit_rule']);    //积分规则

    Route::get('/coin/introduce', ['as' => 'help.coin.introduce', 'uses' => 'HomeController@coin_introduce']);    //L币介绍
});
