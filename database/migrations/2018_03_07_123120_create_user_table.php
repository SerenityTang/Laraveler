<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('id', 36)->index()->primary();

            $table->string('username')->unique()->comment('用户名');
            $table->string('email')->unique()->nullable()->comment('邮箱');
            $table->string('mobile', 50)->unique()->comment('电话号码');
            $table->string('password')->comment('密码');
            $table->tinyInteger('user_status')->default(0)->comment('用户状态：0->禁用，1->正常');
            $table->tinyInteger('user_identity')->default(1)->comment('用户身份：0->超级管理员，1->普通用户，2->访客');
            $table->rememberToken();

            $table->string('avatar')->nullable()->comment('头像');
            $table->string('realname', 50)->nullable()->comment('真实姓名');
            $table->index('realname');
            $table->tinyInteger('gender')->nullable()->comment('性别：0->男，1->女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('province')->nullable()->comment('省');
            $table->index('province');
            $table->string('city')->nullable()->comment('市');
            $table->index('city');
            $table->string('personal_domain')->nullable()->unique()->comment('个性域名');
            $table->string('personal_website')->nullable()->unique()->comment('个人网站');
            $table->longText('description')->nullable()->comment('个人简介');
            $table->string('qq_name')->nullable()->comment('QQ名');
            $table->integer('qq')->nullable()->comment('QQ号码');
            $table->string('wechat_name')->nullable()->comment('微信名');
            $table->string('wechat')->nullable()->comment('微信号');
            $table->string('wechat_qrcode')->nullable()->comment('微信二维码');
            $table->string('wechat_openid')->nullable();
            $table->index('wechat_openid');
            $table->string('wechat_unionid')->nullable();
            $table->index('wechat_unionid');
            $table->string('weibo_name')->nullable()->comment('微博名');
            $table->string('weibo_link')->nullable()->comment('微博链接');
            $table->string('github_name')->nullable()->comment('github名');
            $table->string('github_link')->nullable()->comment('github链接');
            $table->tinyInteger('career_status')->nullable()->comment('职业状态：0->学生，1->在职，2->待业');
            $table->string('career_direction')->nullable()->comment('职业方向');

            $table->string('ques_ans_notifications')->default(0)->comment('问答被回复：0->关闭，1->开启');
            $table->string('blog_comm_notifications')->default(0)->comment('博客被评论：0->关闭，1->开启');
            $table->string('user_atte_notifications')->default(0)->comment('被用户关注：0->关闭，1->开启');
            $table->string('email_notifications')->default(0)->comment('邮件通知策略：0->关闭，1->开启');

            $table->dateTime('approval_time')->nullable()->comment('实名认证时间');
            $table->unsignedTinyInteger('approval_status')->nullable()->default(0)->comment('实名认证状态：0->未提交资料，1->待审核，2->审核通过');
            $table->unsignedTinyInteger('email_status')->default(0)->comment('邮箱认证状态：0->未认证，1->已认证');
            $table->unsignedTinyInteger('mobile_status')->default(0)->comment('手机认证状态：0->未提交资料，1->待审核，2->审核通过');
            $table->unsignedTinyInteger('expert_status')->default(0)->comment('达人认证状态：0->未提交资料，1->待审核，2->审核通过');

            $table->dateTime('last_login_at')->nullable()->comment('最后登录时间');
            $table->dateTime('last_active_at')->nullable()->comment('最后活跃时间');
            $table->dateTime('last_login_ip')->nullable()->comment('最后登录IP');
            $table->dateTime('user_agent')->nullable()->comment('最后登录浏览器信息');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
