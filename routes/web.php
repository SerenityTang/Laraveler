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

//框架自带欢迎页
Route::get('/', function () {
    return view('welcome');
});

//执行认证生成的路由
Auth::routes();

//执行认证生成的首页
Route::get('/home', 'HomeController@index')->name('home');

//登录页面验证码
Route::get('/captcha/verify', ['as' => 'captcha.verify', 'uses' => 'CaptchaController@verify']);
