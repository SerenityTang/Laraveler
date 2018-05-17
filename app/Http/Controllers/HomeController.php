<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Feedback;
use App\Models\Question;
use App\Models\Tag;
use App\Models\UserCreditConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Browser;

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
     * 首页
     */
    public function index()
    {
        $question = new Question();
        $blog = new Blog();
        //$new_questions = call_user_func([$question, 'newest']);     //最新问答
        //$hot_questions = call_user_func([$question, 'hottest']);    //热门问答
        $new_questions = $question->newest(0, 6);       //最新问答
        $hot_questions = $question->hottest(0, 6);      //热门问答
        $new_blogs = $blog->newest(0, 6);       //最新博客
        $hot_blogs = $blog->hottest(0, 6);      //热门博客

        $mnew_questions = $question->newest(0, 6);       //最新问答
        $mhot_questions = $question->hottest(0, 6);      //热门问答
        $mnew_blogs = $blog->newest(0, 6);       //最新博客
        $mhot_blogs = $blog->hottest(0, 6);      //热门博客

        //排行榜
        $active_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.answer_count','DESC')
            ->orderBy('user_datas.article_count','DESC')
            ->orderBy('user.updated_at','DESC')
            ->select('user.id','user.username','user.personal_domain','user.expert_status','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count')
            ->take(10)->get();

        //热门标签
        $tags = Tag::where('status', 1)->get();

        if (Browser::isMobile()) {
            return view('mobile.home')->with(['new_questions' => $mnew_questions, 'hot_questions' => $mhot_questions, 'tags' => $tags, 'new_blogs' => $mnew_blogs, 'hot_blogs' => $mhot_blogs]);
        } else {
            return view('pc.home')->with(['new_questions' => $new_questions, 'hot_questions' => $hot_questions, 'active_users' => $active_users, 'tags' => $tags, 'new_blogs' => $new_blogs, 'hot_blogs' => $hot_blogs]);
        }
        }

    /**
     * 意见反馈
     */
    public function feedback(Request $request)
    {
        $feedback_data = [
            'user_id'       =>Auth::check() ? Auth::user()->id : 0,
            'type'          =>$request->input('feedback'),
            'description'   =>$request->input('description'),
            'url'           =>$request->input('fb-url'),
            //'picture'       $request->input('feedback'),
            'contact'       =>$request->input('fb-contact'),
        ];
        $feedback_data = Feedback::create($feedback_data);
        if ($feedback_data) {
            return $this->jsonResult(801);
        }
    }

    /**
     * 关于我们
     */
    public function about()
    {
        return view('pc.footer.about');
    }

    /**
     * 联系我们
     */
    public function contact()
    {
        return view('pc.footer.contact');
    }

    /**
     * 帮助中心
     */
    public function help()
    {
        return view('pc.footer.help');
    }

    /**
     * 帮助中心之积分介绍
     */
    public function credit_introduce()
    {
        return view('pc.footer.credit.credit_introduce');
    }

    /**
     * 帮助中心之积分规则
     */
    public function credit_rule()
    {
        $user_credit_configs = UserCreditConfig::get();
        return view('pc.footer.credit.credit_rule')->with(['user_credit_configs' => $user_credit_configs]);
    }

    /**
     * 帮助中心之L币介绍
     */
    public function coin_introduce()
    {
        return view('pc.footer.coin.coin_introduce');
    }
}
