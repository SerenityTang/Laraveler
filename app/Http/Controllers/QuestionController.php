<?php

namespace App\Http\Controllers;

use App\Events\QuestionViewEvent;
use App\Models\Answer;
use App\Models\Attention;
use App\Models\Collection;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Http\Request;
use BrowserDetect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = 'newest')
    {
        $question = new Question();
        $questions = call_user_func([$question, $filter]);

        return view('question.index')->with(['questions' => $questions, 'filter' => $filter]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question.create');
    }

    /**
     * Store a newly created resource in storage.
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

        if ($question->question_status == 2 && $answers != null) {
            $best_answer = $answers->where('adopted_at', '>', '0')->first();
        } else {
            return view('question.show')->with(['question' => $question, 'answers' => $answers]);
        }

        return view('question.show')->with(['question' => $question, 'answers' => $answers, 'best_answer' => $best_answer]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::where('id', $id)->first();
        $question->delete();
        if($question->trashed()){
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
            $attention = Attention::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($question))->first();
            //如果此用户关注过此问答，则属于取消关注
            if ($attention) {
                $attention_bool = $attention->delete();
                if ($attention_bool == true) {
                    $question->decrement('attention_count');     //关注数-1
                    $question->save();
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
                    $question->save();
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
            $collection = Collection::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($question))->first();
            //如果此用户关注过此问答，则属于取消关注
            if ($collection) {
                $collection_bool = $collection->delete();
                if ($collection_bool == true) {
                    $question->decrement('collection_count');     //收藏数-1
                    $question->save();
                    return response('uncollection');
                }
            } else {
                //如果此用户无关注过此问答，则属于关注
                $data = [
                    'user_id'           =>$user->id,
                    'entityable_id'   =>$id,
                    'entityable_type' =>get_class($question)
                ];

                $new_collection = Collection::create($data);
                if ($new_collection) {
                    $question->increment('collection_count');     //收藏数+1
                    $question->save();
                    return response('collection');
                }
            }
        } else {
            return view('auth.login');
        }
    }
}
