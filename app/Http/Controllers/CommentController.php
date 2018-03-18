<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * 保存评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {//dd($request->input('to_user'));
        if (Auth::check()) {
            $answer_id = $request->input('answer_id');
            $answer = Answer::where('id', $answer_id)->first();
            $question = Question::where('id', $answer->question_id)->first();
            $user = Auth::user();
            $data = [
                'user_id'           =>$user->id,
                'content'           =>$request->input('comment-part-con'),
                'entity_id'         =>$answer_id,
                'entity_type'       =>$request->input('entity_type'),
                'to_user_id'        =>$request->input('to_user'),
                'status'            =>1,
            ];
            $comment = Comment::create($data);

            if ($comment) {
                //回答评论数
                if ($answer->comment_count == 0) {
                    $answer->comment_count = 1;
                } else {
                    $answer->comment_count++;
                }
                $answer->save();

                //问题评论数
                if ($question->comment_count == 0) {
                    $question->comment_count = 1;
                } else {
                    $question->comment_count++;
                }
                $question->save();

                return view('comment.comment_part_item')->with(['comment' => $comment]);
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 读取评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($entity_id, $entity_type)
    {
        $comments = Comment::where('entity_id', $entity_id)->where('entity_type', $entity_type)->orderBy('created_at','asc')->get();
        $answer = Answer::where('id', $entity_id)->first();
        return view('comment.comment_part')->with(['comments' => $comments, 'entity_id' => $entity_id, 'entity_type' => $entity_type, 'answer' => $answer]);
    }
}
