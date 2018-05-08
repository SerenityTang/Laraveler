<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkshowController extends Controller
{
    /**
     * 作品展示首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pc.workshow.index');
    }
}
