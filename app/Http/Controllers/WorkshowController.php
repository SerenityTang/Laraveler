<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Browser;

class WorkshowController extends Controller
{
    /**
     * 作品展示首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Browser::isMobile()) {
            return view('mobile.workshow.index');
        }
        return view('pc.workshow.index');
    }
}
