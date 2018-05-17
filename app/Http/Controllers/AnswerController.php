<?php

namespace App\Http\Controllers;

use App\Events\AnswerOperationCreditEvent;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Support_opposition;
use App\Models\User_data;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class AnswerController extends Controller
{
    /**
     * 保存问答回复
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $question_id = $request->input('question_id');
            $question = Question::where('id', $question_id)->first();
            $data = [
                'question_title'        =>$question->title,
                'question_id'           =>$question_id,
                'user_id'               =>$user->id,
                'content'               =>$request->input('answer-content'),
                'device'                =>1,
                'status'                =>1,
            ];
            $answer = Answer::create($data);

            if ($answer) {
                //问答回答数
                if ($question->answer_count == 0) {
                    $question->answer_count = 1;
                } else {
                    $question->answer_count++;
                }
                //问答的问题状态
                $question->question_status = 1;
                $question->save();

                $user_data = User_data::where('user_id', $user->id)->first();
                $user_data->increment('answer_count');

                //触发回答问题加分事件
                Event::fire(new AnswerOperationCreditEvent($user, 'answer'));

                return $this->success(route('question.show', ['id' => $question_id]), '回复成功^_^');
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 设置问答的回复为最佳
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adopt($id)
    {
        $answer = Answer::where('id', $id)->first();
        $user_data = User_data::where('user_id', $answer->user_id)->first();
        $user = $answer->user;
        $answer->adopted_at = Carbon::now()->toDateTimeString();
        $ans_bool = $answer->save();

        if ($ans_bool == true) {
            $question = Question::where('id', $answer->question_id)->first();
            $question->question_status = 2;
            $ques_bool = $question->save();

            if ($ques_bool == true) {
                $user_data->increment('adoption_count');

                //触发回答被采纳加分事件
                Event::fire(new AnswerOperationCreditEvent($user, 'adopt'));

                return $this->jsonResult(703);
            }
        }
    }

    /**
     * 支持问答的回复
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function support($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $answer = Answer::where('id', $id)->first();
            $curr_user_data = User_data::where('user_id', $user->id)->first();
            $user_data = User_data::where('user_id', $answer->user_id)->first();
            $sup_opp = Support_opposition::where('user_id', $user->id)->where('sup_opp_able_id', $id)->where('sup_opp_able_type', get_class($answer))->first();
            $answer_user = $answer->user;

            //如果此用户支持过此问答的回答，则属于取消支持
            if ($sup_opp) {
                $del_bool = $sup_opp->delete();
                if ($del_bool == true) {
                    $answer->decrement('support_count');   //回答支持数-1
                    $curr_user_data->decrement('support_count'); //当前用户支持数-1
                    $user_data->decrement('supported_count'); //回答所属用户被支持数-1

                    //取消支持，扣取支持添加的积分
                    Event::fire(new AnswerOperationCreditEvent($answer_user, 'support', 'no'));

                    return response('unsupport');
                }
            } else {
                //如果此用户无支持过此问答的回答，则属于支持
                $data = [
                    'user_id'               =>$user->id,
                    'sup_opp_able_id'       =>$id,
                    'sup_opp_able_type'     =>get_class($answer),
                    'sup_opp_mode'          =>'support',
                ];
                $support_opposition = Support_opposition::create($data);

                if ($support_opposition) {
                    $answer->increment('support_count');      //回答支持数+1
                    $curr_user_data->increment('support_count'); //当前用户支持数+1
                    $user_data->increment('supported_count'); //回答所属用户被支持数+1

                    //支持，添加支持积分
                    Event::fire(new AnswerOperationCreditEvent($answer_user, 'support', 'yes'));

                    return response('support');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 反对问答的回复
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function oppose($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $answer = Answer::where('id', $id)->first();
            $sup_opp = Support_opposition::where('user_id', $user->id)->where('sup_opp_able_id', $id)->where('sup_opp_able_type', get_class($answer))->first();
            //如果此用户反对过此问答的回答，则属于取消反对
            if ($sup_opp) {
                $del_bool = $sup_opp->delete();
                if ($del_bool == true) {
                    $answer->decrement('opposition_count');   //回答反对数-1

                    return response('unopposition');
                }
            } else {
                //如果此用户无反对过此问答的回答，则属于反对
                $data = [
                    'user_id'               =>$user->id,
                    'sup_opp_able_id'       =>$id,
                    'sup_opp_able_type'     =>get_class($answer),
                    'sup_opp_mode'          =>'opposition',
                ];
                $support_opposition = Support_opposition::create($data);

                if ($support_opposition) {
                    $answer->increment('opposition_count');      //回答反对数+1

                    return response('opposition');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 问答的回复按条件显示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort_show($id, $sort)
    {
        $question = Question::where('id', $id)->first();
        if ($sort === 'default') {
            $answers = Answer::where('question_id', $id)->where('status', 1)->get();
        } else if ($sort === 'time') {
            $answers = Answer::where('question_id', $id)->where('status', 1)->orderBy('created_at', 'DESC')->get();
        } else if ($sort === 'support') {
            $answers = Answer::where('question_id', $id)->where('status', 1)->orderBy('support_count', 'DESC')->get();
        }

        return view('pc.question.parts.answer')->with(['answers' => $answers, 'question' => $question]);
    }
}
