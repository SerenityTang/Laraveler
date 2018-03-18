<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/1/27
 * Time: 下午5:44
 */

Route::any('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);