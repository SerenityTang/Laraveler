<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $question = new Question();
        $new_questions = call_user_func([$question, 'newest']);     //最新问答
        $hot_questions = call_user_func([$question, 'hottest']);    //热门问答

        //排行榜
        $active_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.answer_count','DESC')
            ->orderBy('user_datas.article_count','DESC')
            ->orderBy('user.updated_at','DESC')
            ->select('user.id','user.username','user.personal_domain','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count','user_datas.expert_status')
            ->take(10)->get();

        //热门标签
        $tags = Tag::where('status', 1)->get();

        return view('home')->with(['new_questions' => $new_questions, 'hot_questions' => $hot_questions, 'active_users' => $active_users, 'tags' => $tags]);
    }
}
