<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/2/10
 * Time: 下午3:44
 */

Route::group(['prefix' => 'user'], function() {
    Route::get('/active_rank', ['as' => 'user.active_rank', 'uses' => 'UserController@active_rank']);       //活跃排行榜
    Route::get('/credit_rank', ['as' => 'user.credit_rank', 'uses' => 'UserController@credit_rank']);       //积分排行榜
    Route::get('/email_bind/verify', ['as' => 'user.email_bind.verify', 'uses' => 'UserController@activate_email_bind']);    //激活邮箱绑定
});
/*个人主页*/
Route::group(['prefix' => 'user/{personal_domain}'], function() {
    Route::get('/', ['as' => 'user.index', 'uses' => 'UserController@index']);
    Route::get('/questions', ['as' => 'user.questions', 'uses' => 'UserController@questions']);
    Route::get('/answers', ['as' => 'user.answers', 'uses' => 'UserController@answers']);
    Route::get('/blogs', ['as' => 'user.blogs', 'uses' => 'UserController@blogs']);
    Route::get('/attentions', ['as' => 'user.attentions', 'uses' => 'UserController@attentions']);
    Route::get('/fans', ['as' => 'user.fans', 'uses' => 'UserController@fans']);
    Route::get('/supports', ['as' => 'user.supports', 'uses' => 'UserController@supports']);
    Route::get('/collections', ['as' => 'user.collections', 'uses' => 'UserController@collections']);
    Route::get('/drafts', ['as' => 'user.drafts', 'uses' => 'UserController@drafts']);
});

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function() {
    Route::post('/attention_user', ['as' => 'user.attention_user', 'uses' => 'UserController@attention_user']);
    /*个人设置*/
    Route::get('{username}/settings', ['as' => 'user.settings', 'uses' => 'UserController@settings']);
    Route::get('{username}/authenticate', ['as' => 'user.authenticate', 'uses' => 'UserController@authenticate']);
    Route::get('{username}/edit_password', ['as' => 'user.edit_password', 'uses' => 'UserController@edit_password']);
    Route::get('{username}/edit_notify', ['as' => 'user.edit_notify', 'uses' => 'UserController@edit_notify']);
    Route::get('{username}/security', ['as' => 'user.security', 'uses' => 'UserController@security']);
    Route::get('{username}/bindsns', ['as' => 'user.bindsns', 'uses' => 'UserController@bindsns']);
    Route::get('{username}/career_status', ['as' => 'user.career_status', 'uses' => 'UserController@career_status']);

    /*用户个人相关信息修改*/
    Route::post('auth/profile/per_detail', ['as' => 'user.auth.profile.per_detail', 'uses' => 'UserController@per_detail']);    //修改个人信息
    Route::post('auth/profile/avatar', ['as' => 'user.auth.profile.avatar', 'uses' => 'UserController@post_avatar']);   //修改头像
    Route::post('profile/modify_password', ['as' => 'user.profile.modify_password', 'uses' => 'UserController@modify_password']);   //修改密码

    /*账号安全*/
    Route::post('/email_bind', ['as' => 'user.email_bind', 'uses' => 'UserController@email_bind']);    //提交邮箱绑定
    Route::post('/email_bind/send_verify', ['as' => 'user.email_bind.send_verify', 'uses' => 'UserController@send_verify']);    //验证邮箱
    Route::post('/verify_email', ['as' => 'user.verify_email', 'uses' => 'UserController@verify_email']);    //更换邮箱前验证

    Route::post('/mobile_bind', ['as' => 'user.mobile_bind', 'uses' => 'UserController@mobile_bind']);    //提交手机绑定手机号
    Route::post('/change_mobile_bind', ['as' => 'user.change_mobile_bind', 'uses' => 'UserController@change_mobile_bind']);    //提交更换手机绑定手机号
    Route::post('/verify_mobile_code', ['as' => 'user.verify_mobile_code', 'uses' => 'UserController@verify_mobile_code']);    //提交手机绑定手机号
    Route::post('/verify_mobile', ['as' => 'user.verify_mobile', 'uses' => 'UserController@verify_mobile']);    //更换手机前验证

    /*账号解除绑定*/
    Route::get('/unbind/{driver}', ['as' => 'user.unbind', 'uses' => 'UserController@social_unbind']);    //提交手机绑定手机号

    Route::post('/notify', ['as' => 'user.notify', 'uses' => 'UserController@notify']);    //通知私信

    Route::post('/signIn', ['as' => 'user.signIn', 'uses' => 'UserController@signIn']);    //签到
});




