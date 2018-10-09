<?php

namespace App\Http\Controllers;

use App\Events\QuesOperationCreditEvent;
use App\Events\QuestionCreditEvent;
use App\Events\QuestionViewEvent;
use App\Models\Answer;
use App\Models\Attention;
use App\Models\Collection;
use App\Models\PersonalDynamic;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\User_data;
use App\Models\Vote;
use Illuminate\Http\Request;
use BrowserDetect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Browser;

class QuestionController extends Controller
{
    /**
     * 问答首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = 'newest')
    {
        $question = new Question();
        $questions = call_user_func([$question, $filter]);

        //最新回答
        $new_answer_ques = [];
        $answers = Answer::where('status', 1)->orderBy('created_at', 'DESC')->get();
        foreach ($answers as $answer) {
            $question = $answer->question;
            array_push($new_answer_ques, $question);
            $new_answer_questions = array_unique($new_answer_ques);
        }

        //问答热门标签
        $taggables = Taggable::where('taggable_type', get_class($question))->get();
        $tags = array();
        foreach ($taggables as $taggable) {
            $tag = Tag::where('id', $taggable->tag_id)->first();
            array_push($tags, $tag);
            $hot_tags = array_unique($tags);
        }

        //热心排行榜
        $week = Carbon::now()->dayOfWeek;
        if ($week == 0) {
            $start = Carbon::now()->addDays($week - 7);
            $end = Carbon::now();
        } else {
            $start = Carbon::now()->addDays($week - 7);
            $end = Carbon::now()->addDays(7 - $week);
        }
        $warm_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status', '>', 0)->where('user_datas.answer_count', '>', 0)
            ->whereBetween('user_datas.updated_at', [$start, $end])
            ->orderBy('user_datas.answer_count', 'DESC')
            ->select('user.id', 'user.username', 'user.personal_domain', 'user_datas.answer_count')
            ->take(9)->get();

        if (Browser::isMobile()) {
            $question = new Question();
            $newest_ques = call_user_func([$question, 'newest']);
            $hottest_ques = call_user_func([$question, 'hottest']);
            $reward_ques = call_user_func([$question, 'reward']);
            $unanswer_ques = call_user_func([$question, 'unanswer']);
            $adopt_ques = call_user_func([$question, 'adopt']);

            return view('mobile.question.index')->with(['newest_ques' => $newest_ques, 'hottest_ques' => $hottest_ques, 'reward_ques' => $reward_ques, 'unanswer_ques' => $unanswer_ques, 'adopt_ques' => $adopt_ques]);
        }

        if (!isset($new_answer_questions) && !isset($hot_tags)) {
            return view('pc.question.index')->with(['questions' => $questions, 'filter' => $filter, 'warm_users' => $warm_users]);
        } else if (!isset($hot_tags)) {
            return view('pc.question.index')->with(['questions' => $questions, 'filter' => $filter, 'new_answer_questions' => $new_answer_questions, 'warm_users' => $warm_users]);
        } else if (!isset($new_answer_questions)) {
            return view('pc.question.index')->with(['questions' => $questions, 'filter' => $filter, 'hot_tags' => $hot_tags, 'warm_users' => $warm_users]);
        }

        return view('pc.question.index')->with(['questions' => $questions, 'filter' => $filter, 'new_answer_questions' => $new_answer_questions, 'warm_users' => $warm_users, 'hot_tags' => $hot_tags]);
    }

    /**
     * 发布问答页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::where('status', 1)->get();
        if (Auth::check()) {
            $user_data = User_data::where('user_id', Auth::user()->id)->first();
        } else {
            return view('pc.auth.login');
        }

        return view('pc.question.create')->with(['tags' => $tags, 'user_data' => $user_data]);
    }

    /**
     * 保存发布问答
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'user_id' => $user->id,
                'qcategory_id' => $request->input('qcategory_id', 0),
                'title' => $request->input('question_title'),
                'description' => $request->input('desc'),
                'price' => $request->input('price'),
                //'device'        =>BrowserDetect::deviceModel() == '' ? BrowserDetect::deviceModel() : 'PC',
                'device' => 1,
                'status' => 1,
            ];
            $question = Question::create($data);

            //判断是否发布成功
            if ($question) {
                $user_data = User_data::where('user_id', $user->id)->first();
                $user_data->increment('question_count');

                //绑定标签
                $tags = explode(',', $request->input('tags'));
                foreach ($tags as $tag) {
                    $taggables = [
                        'tag_id' => $tag,
                        'taggable_id' => $question->id,
                        'taggable_type' => get_class($question),
                    ];
                    $taggable = Taggable::create($taggables);
                }
                if ($taggable) {
                    //触发添加积分事件
                    Event::fire(new QuestionCreditEvent($user));

                    //添加动态
                    $data = [
                        'user_id' => $user->id,
                        'source_id' => $question->id,
                        'source_type' => get_class($question),
                        'action' => 'publishQues',
                        'title' => $question->title,
                        'content' => $question->description,
                    ];
                    $per_dyn = PersonalDynamic::create($data);
                    if ($per_dyn) {
                        return $this->success('/question', '发布问答成功，请耐心等待并留意热心朋友为您提供解答^_^');
                    }

                } else {
                    return $this->error('/question', '发布问答失败，未绑定标签^_^');
                }
            } else {
                return $this->error('/question', '发布问答失败^_^');
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 保存问答草稿
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_draft(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'user_id' => $user->id,
                'qcategory_id' => $request->input('qcategory_id', 0),
                'title' => $request->input('question_title'),
                'description' => $request->input('desc'),
                'price' => $request->input('price'),
                //'device'        =>BrowserDetect::deviceModel() == '' ? BrowserDetect::deviceModel() : 'PC',
                'device' => 1,
                'status' => 2,
            ];
            $question = Question::create($data);

            //判断是否保存草稿
            if ($question) {
                //绑定标签
                $tags = explode(',', $request->input('tags'));
                foreach ($tags as $tag) {
                    $taggables = [
                        'tag_id' => $tag,
                        'taggable_id' => $question->id,
                        'taggable_type' => get_class($question),
                    ];
                    $taggable = Taggable::create($taggables);
                }
                if ($taggable) {
                    return $this->jsonResult(501, '保存草稿成功，请前往个人主页查看^_^');
                } else {
                    return $this->jsonResult(502, '保存草稿失败，未绑定标签^_^');
                }
            } else {
                return $this->jsonResult(502, '保存草稿失败^_^');
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 展示问答内容页
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::where('id', $id)->first();
        $answers = Answer::where('question_id', $id)->get();

        //问答浏览量统计
        Event::fire(new QuestionViewEvent($question));

        //其它问答
        $other_ques = Question::where('user_id', $question->user_id)->where('status', 1)->get();
        foreach ($other_ques as $other_quekey => $other_quevalue) {
            if ($other_quevalue->id == $id) {
                unset($other_ques[$other_quekey]);
            }
        }

        //相关问答
        $tag_id = $question->tags()->pluck('tag_id');
        $correlation_ques = $question->whereHas('tags', function ($query) use ($tag_id) {
            $query->whereIn('tag_id', $tag_id);
        })->where('status', 1)->orderBy('created_at', 'DESC')->take(8)->get();

        //最佳答案
        if ($question->question_status == 2 && $answers != null) {
            $best_answer = $answers->where('adopted_at', '>', '0')->first();
        } else {
            return view('pc.question.show')->with(['question' => $question, 'answers' => $answers, 'other_ques' => $other_ques, 'correlation_ques' => $correlation_ques]);
        }

        return view('pc.question.show')->with(['question' => $question, 'answers' => $answers, 'best_answer' => $best_answer, 'other_ques' => $other_ques, 'correlation_ques' => $correlation_ques]);
    }

    /**
     * 展示问答最佳答案
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show_best_answer($id)
    {
        $best_answer = Answer::where('id', $id)->first();
        $question = Question::where('id', $best_answer->question_id)->first();
        if ($best_answer) {
            return view('pc.question.parts.best_answer')->with(['best_answer' => $best_answer, 'question' => $question]);
        }
    }

    /**
     * 展示问答编辑页
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show_edit($id)
    {
        $question = Question::where('id', $id)->first();
        $tags = Tag::where('status', 1)->get();     //获取tag展示在下拉列表
        //获取此问答绑定的标签
        $bound_tags = [];
        $taggables = Taggable::where('taggable_id', $question->id)->where('taggable_type', get_class($question))->get();
        foreach ($taggables as $taggable) {
            $tag = Tag::where('id', $taggable->tag_id)->first();
            array_push($bound_tags, $tag);
        }

        if (Auth::check()) {
            $user_data = User_data::where('user_id', Auth::user()->id)->first();
        } else {
            return view('pc.auth.login');
        }

        return view('pc.question.edit')->with(['question' => $question, 'user_data' => $user_data, 'tags' => $tags, 'bound_tags' => $bound_tags]);
    }

    /**
     * 保存修改问答
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (Auth::check()) {
            $question = Question::where('id', $id)->first();
            $ori_status = $question->status;
            $question->qcategory_id = $request->input('qcategory_id');
            $question->title = $request->input('question_title');
            $question->description = $request->input('desc');
            $question->price = $request->input('price');
            $question->status = 1;
            $bool = $question->save();

            if ($bool == true) {
                if ($ori_status == 2) {
                    $user_data = User_data::where('user_id', Auth::user()->id)->first();
                    $user_data->increment('question_count');

                    return $this->success(route('question.show', $id), '您的问题草稿以问答方式发布成功^_^');
                } else {
                    return $this->success(route('question.show', $id), '问题编辑成功^_^');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 删除问答
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::where('id', $id)->first();
        $user_data = User_data::where('user_id', $question->user_id)->first();
        $question->delete();
        if ($question->trashed()) {
            $user_data->decrement('question_count');

            return $this->jsonResult(701);
        } else {
            return $this->jsonResult(702);
        }
    }

    /**
     * 舍弃问答
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function abandon($id)
    {
        $question = Question::where('id', $id)->first();

        $question->delete();
        if ($question->trashed()) {
            return $this->jsonResult(704);
        } else {
            return $this->jsonResult(705);
        }
    }

    /**
     * 问答投票
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function vote($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $question = Question::where('id', $id)->first();
            $vote = Vote::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($question))->first();
            $ques_user = $question->user;

            //如果此用户投票过此问答，则属于取消投票
            if ($vote) {
                $vote_bool = $vote->delete();
                if ($vote_bool == true) {
                    $question->decrement('vote_count');     //投票数-1
                    $question->save();

                    //取消投票，扣取投票添加的积分
                    Event::fire(new QuesOperationCreditEvent($ques_user, 'vote', 'no'));

                    return response('unvote');
                }
            } else {
                //如果此用户无投票过此问答，则属于投票
                $data = [
                    'user_id' => $user->id,
                    'entityable_id' => $id,
                    'entityable_type' => get_class($question)
                ];

                $new_vote = Vote::create($data);
                if ($new_vote) {
                    $question->increment('vote_count');     //投票数+1
                    $question->save();

                    //投票，添加投票积分
                    Event::fire(new QuesOperationCreditEvent($ques_user, 'vote', 'yes'));

                    //添加动态
                    $data = [
                        'user_id' => $user->id,
                        'source_id' => $question->id,
                        'source_type' => get_class($question),
                        'action' => 'voteQues',
                        'title' => $question->title,
                        'content' => $question->description,
                    ];
                    $per_dyn = PersonalDynamic::create($data);
                    if ($per_dyn) {
                        return response('vote');
                    }
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 问答关注
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function attention($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $question = Question::where('id', $id)->first();
            $curr_user_data = User_data::where('user_id', $user->id)->first();
            $user_data = User_data::where('user_id', $question->user_id)->first();
            $attention = Attention::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($question))->first();
            //如果此用户关注过此问答，则属于取消关注
            if ($attention) {
                $attention_bool = $attention->delete();
                if ($attention_bool == true) {
                    $question->decrement('attention_count');     //关注数-1
                    $curr_user_data->decrement('atten_count'); //当前用户关注数-1
                    $user_data->decrement('attened_count'); //回答所属用户被关注数-1

                    return response('unattention');
                }
            } else {
                //如果此用户无关注过此问答，则属于关注
                $data = [
                    'user_id' => $user->id,
                    'entityable_id' => $id,
                    'entityable_type' => get_class($question)
                ];

                $new_attention = Attention::create($data);
                if ($new_attention) {
                    $question->increment('attention_count');     //关注数+1
                    $curr_user_data->increment('atten_count'); //当前用户关注数+1
                    $user_data->increment('attened_count'); //回答所属用户被关注数+1

                    //添加动态
                    $data = [
                        'user_id' => $user->id,
                        'source_id' => $question->id,
                        'source_type' => get_class($question),
                        'action' => 'attentionQues',
                        'title' => $question->title,
                        'content' => $question->description,
                    ];
                    $per_dyn = PersonalDynamic::create($data);
                    if ($per_dyn) {
                        return response('attention');
                    }
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 问答收藏
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function collection($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $question = Question::where('id', $id)->first();
            $curr_user_data = User_data::where('user_id', $user->id)->first();
            $user_data = User_data::where('user_id', $question->user_id)->first();
            $collection = Collection::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($question))->first();
            $ques_user = $question->user;

            //如果此用户关注过此问答，则属于取消收藏
            if ($collection) {
                $collection_bool = $collection->delete();
                if ($collection_bool == true) {
                    $question->decrement('collection_count');     //收藏数-1
                    $curr_user_data->decrement('collection_count'); //当前用户收藏数-1
                    $user_data->decrement('collectioned_count'); //回答所属用户被收藏数-1

                    //取消收藏，扣取收藏添加的积分
                    Event::fire(new QuesOperationCreditEvent($ques_user, 'collection', 'no'));

                    return response('uncollection');
                }
            } else {
                //如果此用户无关注过此问答，则属于收藏
                $data = [
                    'user_id' => $user->id,
                    'entityable_id' => $id,
                    'entityable_type' => get_class($question)
                ];

                $new_collection = Collection::create($data);
                if ($new_collection) {
                    $question->increment('collection_count');     //收藏数+1
                    $curr_user_data->increment('collection_count'); //当前用户收藏数+1
                    $user_data->increment('collectioned_count'); //回答所属用户被收藏数+1

                    //收藏，添加收藏积分
                    Event::fire(new QuesOperationCreditEvent($ques_user, 'collection', 'yes'));

                    //添加动态
                    $data = [
                        'user_id' => $user->id,
                        'source_id' => $question->id,
                        'source_type' => get_class($question),
                        'action' => 'collectionQues',
                        'title' => $question->title,
                        'content' => $question->description,
                    ];
                    $per_dyn = PersonalDynamic::create($data);
                    if ($per_dyn) {
                        return response('collection');
                    }
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 热门问答日榜
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function day_sort()
    {
        $day_hot_questions = Question::where('status', 1)->where('view_count', '>', 10)->whereDate('updated_at', date('Y-m-d'))->orderBy('view_count', 'DESC')->orderBy('answer_count', 'DESC')->orderBy('created_at', 'DESC')->paginate(15);
        return view('pc.question.parts.day_sort')->with(['day_hot_questions' => $day_hot_questions]);
    }

    /**
     * 热门问答周榜
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function week_sort()
    {
        $week = Carbon::now()->dayOfWeek;
        if ($week == 0) {
            $start = Carbon::now()->addDays($week - 7);
            $end = Carbon::now();
        } else {
            $start = Carbon::now()->addDays($week - 7);
            $end = Carbon::now()->addDays(7 - $week);
        }

        $week_hot_questions = Question::where('status', 1)->where('view_count', '>', 10)->whereBetween('updated_at', [$start, $end])->orderBy('view_count', 'DESC')->orderBy('answer_count', 'DESC')->orderBy('created_at', 'DESC')->paginate(15);
        return view('pc.question.parts.week_sort')->with(['week_hot_questions' => $week_hot_questions]);
    }

    /**
     * 热门问答月榜
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function month_sort()
    {
        $now_day = Carbon::now();
        $start = date('Y-m-01', strtotime($now_day));//获取指定月份的第一天
        $end = date('Y-m-t', strtotime($now_day)); //获取指定月份的最后一天

        $month_hot_questions = Question::where('status', 1)->where('view_count', '>', 10)->whereBetween('updated_at', [$start, $end])->orderBy('view_count', 'DESC')->orderBy('answer_count', 'DESC')->orderBy('created_at', 'DESC')->paginate(15);
        return view('pc.question.parts.month_sort')->with(['month_hot_questions' => $month_hot_questions]);
    }

    /**
     * 热心周排行榜
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function warm_week()
    {
        $week = Carbon::now()->dayOfWeek;
        if ($week == 0) {
            $start = Carbon::now()->addDays($week - 7);
            $end = Carbon::now();
        } else {
            $start = Carbon::now()->addDays($week - 7);
            $end = Carbon::now()->addDays(7 - $week);
        }

        $warm_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status', '>', 0)->where('user_datas.answer_count', '>', 0)
            ->whereBetween('user_datas.updated_at', [$start, $end])
            ->orderBy('user_datas.answer_count', 'DESC')
            ->select('user.id', 'user.username', 'user.personal_domain', 'user_datas.answer_count')
            ->take(9)->get();
        return view('pc.question.parts.warm_week')->with(['warm_users' => $warm_users]);
    }

    /**
     * 热心月排行榜
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function warm_month()
    {
        $now_day = Carbon::now();
        $start = date('Y-m-01', strtotime($now_day));//获取指定月份的第一天
        $end = date('Y-m-t', strtotime($now_day)); //获取指定月份的最后一天

        $warm_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status', '>', 0)->where('user_datas.answer_count', '>', 0)
            ->whereBetween('user_datas.updated_at', [$start, $end])
            ->orderBy('user_datas.answer_count', 'DESC')
            ->select('user.id', 'user.username', 'user.personal_domain', 'user_datas.answer_count')
            ->take(9)->get();
        return view('pc.question.parts.warm_month')->with(['warm_users' => $warm_users]);
    }
}
