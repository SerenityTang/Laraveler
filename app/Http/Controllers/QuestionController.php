<?php

namespace App\Http\Controllers;

use App\Events\QuestionViewEvent;
use App\Models\Answer;
use App\Models\Attention;
use App\Models\Collection;
use App\Models\Question;
use App\Models\User_data;
use App\Models\Vote;
use Illuminate\Http\Request;
use BrowserDetect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

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

        $active_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.answer_count','DESC')
            ->orderBy('user_datas.article_count','DESC')
            ->orderBy('user.updated_at','DESC')
            ->select('user.id','user.username','user.personal_domain','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count','user_datas.expert_status')
            ->take(10)->get();

        return view('question.index')->with(['questions' => $questions, 'filter' => $filter, 'new_answer_questions' => $new_answer_questions, 'active_users' => $active_users]);
    }

    /**
     * 发布问答页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question.create');
    }

    /**
     * 活跃排行榜
     *
     * @return \Illuminate\Http\Response
     */
    public function active_rank()
    {
        $active_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.answer_count','DESC')
            ->orderBy('user_datas.article_count','DESC')
            ->orderBy('user.updated_at','DESC')
            ->select('user.id','user.username','user.personal_domain','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count','user_datas.expert_status')
            ->take(10)->get();

        return view('question.parts.active_rank')->with(['active_users' => $active_users]);
    }

    /**
     * 积分排行榜
     *
     * @return \Illuminate\Http\Response
     */
    public function credit_rank()
    {
        $credit_users = DB::table('user_datas')->leftJoin('user', 'user.id', '=', 'user_datas.user_id')
            ->where('user.user_status','>',0)
            ->orderBy('user_datas.credits','DESC')
            ->select('user.id','user.username','user.personal_domain','user_datas.coins','user_datas.credits','user_datas.attention_count','user_datas.support_count','user_datas.answer_count','user_datas.article_count','user_datas.expert_status')
            ->take(10)->get();

        return view('question.parts.credit_rank')->with(['credit_users' => $credit_users]);
    }

    /**
     * 保存发布问答
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $data = [
                'user_id'       =>$user->id,
                'qcategory_id'  =>$request->input('qcategory_id', 0),
                'title'         =>$request->input('question_title'),
                'description'   =>$request->input('description'),
                'price'         =>0,
                //'device'        =>BrowserDetect::deviceModel() == '' ? BrowserDetect::deviceModel() : 'PC',
                'device'        =>1,
                'status'        =>1,
            ];
            $question = Question::create($data);

            //判断是否发布成功
            if ($question) {
                $user_data = User_data::where('user_id', $user->id)->first();
                $user_data->increment('question_count');

                return $this->success('/question', '发布问答成功，请耐心等待并留意热心朋友为您提供解答^_^！！！');
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 展示问答内容页
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::where('id', $id)->first();
        $answers = Answer::where('question_id', $id)->get();

        //问答浏览量统计
        Event::fire(new QuestionViewEvent($question));

        //其它问答
        $other_ques = Question::where('user_id', $question->user_id)->get();
        foreach ($other_ques as $other_quekey => $other_quevalue) {
            if ($other_quevalue->id == $id) {
                unset($other_ques[$other_quekey]);
            }
        }

        //最佳答案
        if ($question->question_status == 2 && $answers != null) {
            $best_answer = $answers->where('adopted_at', '>', '0')->first();
        } else {
            return view('question.show')->with(['question' => $question, 'answers' => $answers, 'other_ques' => $other_ques]);
        }

        return view('question.show')->with(['question' => $question, 'answers' => $answers, 'best_answer' => $best_answer, 'other_ques' => $other_ques]);
    }

    /**
     * 展示问答编辑页
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_edit($id)
    {
        $question = Question::where('id', $id)->first();
        return view('question.edit')->with(['question' => $question]);
    }

    /**
     * 展示问答最佳答案
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_best_answer($id)
    {
        $best_answer = Answer::where('id', $id)->first();
        $question = Question::where('id', $best_answer->question_id)->first();
        if ($best_answer) {
            return view('question.parts.best_answer')->with(['best_answer' => $best_answer, 'question' => $question]);
        }
    }

    /**
     * 保存修改问答
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $question = Question::where('id', $id)->first();
        $question->qcategory_id = $request->input('qcategory_id');
        $question->title = $request->input('question_title');
        $question->description = $request->input('description');
        $bool = $question->save();

        if ($bool == true) {
            return $this->success(route('question.show', $id), '问题编辑成功^_^');
        }
    }

    /**
     * 删除问答
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::where('id', $id)->first();
        $user_data = User_data::where('user_id', $question->user_id)->first();
        $question->delete();
        if($question->trashed()){
            $user_data->decrement('question_count');

            return $this->jsonResult(701);
        }else{
            return $this->jsonResult(702);
        }
    }

    /**
     * 问答投票
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vote($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $question = Question::where('id', $id)->first();
            $vote = Vote::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($question))->first();
            //如果此用户投票过此问答，则属于取消投票
            if ($vote) {
                $vote_bool = $vote->delete();
                if ($vote_bool == true) {
                    $question->decrement('vote_count');     //投票数-1
                    $question->save();
                    return response('unvote');
                }
            } else {
                //如果此用户无投票过此问答，则属于投票
                $data = [
                    'user_id'           =>$user->id,
                    'entityable_id'   =>$id,
                    'entityable_type' =>get_class($question)
                ];

                $new_vote = Vote::create($data);
                if ($new_vote) {
                    $question->increment('vote_count');     //投票数+1
                    $question->save();
                    return response('vote');
                }
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 问答关注
     *
     * @param  int  $id
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
                    'user_id'           =>$user->id,
                    'entityable_id'   =>$id,
                    'entityable_type' =>get_class($question)
                ];

                $new_attention = Attention::create($data);
                if ($new_attention) {
                    $question->increment('attention_count');     //关注数+1
                    $curr_user_data->increment('atten_count'); //当前用户关注数+1
                    $user_data->increment('attened_count'); //回答所属用户被关注数+1

                    return response('attention');
                }
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 问答收藏
     *
     * @param  int  $id
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
            //如果此用户关注过此问答，则属于取消收藏
            if ($collection) {
                $collection_bool = $collection->delete();
                if ($collection_bool == true) {
                    $question->decrement('collection_count');     //收藏数-1
                    $curr_user_data->decrement('collection_count'); //当前用户收藏数-1
                    $user_data->decrement('collectioned_count'); //回答所属用户被收藏数-1

                    return response('uncollection');
                }
            } else {
                //如果此用户无关注过此问答，则属于收藏
                $data = [
                    'user_id'           =>$user->id,
                    'entityable_id'   =>$id,
                    'entityable_type' =>get_class($question)
                ];

                $new_collection = Collection::create($data);
                if ($new_collection) {
                    $question->increment('collection_count');     //收藏数+1
                    $curr_user_data->increment('collection_count'); //当前用户收藏数+1
                    $user_data->increment('collectioned_count'); //回答所属用户被收藏数+1

                    return response('collection');
                }
            }
        } else {
            return view('auth.login');
        }
    }
}
