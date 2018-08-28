<?php

namespace App\Http\Controllers;

use Cookie;
use Session;
use Minho\Captcha\CaptchaBuilder;

class CaptchaController extends Controller
{
    /**
     * 显示验证码
     * @return mixed
     */
    public function verify()
    {
        $builder = new CaptchaBuilder();

        $builder->initialize([
            'width' => 110,     // 宽度
            'height' => 40,     // 高度
            'line' => false,     // 直线
            'curve' => true,   // 曲线
            'noise' => false,   // 噪点背景
            'fonts' => []       // 字体
        ]);



        $builder->create()->output(1);

        $phrase = $builder->getText();
        //Session::flash('milkcaptcha', $phrase);
        //  session(['milkcaptcha' => $phrase]);
        Session::put('milkcaptcha', $phrase);
        // $_SESSION['milkcaptcha'] = $phrase;
        Session::save();

//        header('Content-type: image/jpeg');
//        $fonts = Storage::allFiles('fonts');
//
//        $builder = new CaptchaBuilder();
//        $builder->setInterpolation(0);
//        $font = storage_path('app/' . $fonts[mt_rand(0,count($fonts)-1)]);
//
//        $builder->build(110,34,$font);
//
//        //获取验证码的内容
//        $phrase = $builder->getPhrase();
//
//       // 把内容存入session
//        Session::flash('milkcaptcha', $phrase);
//        $builder->output();
//
//
//        header('Content-type: image/jpeg');
        return ;
    }
}
