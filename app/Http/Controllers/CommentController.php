<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Question;
use App\Models\User_data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * 保存回答评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function answer_store(Request $request)
    {
        if (Auth::check()) {
            $answer_id = $request->input('answer_id');
            $answer = Answer::where('id', $answer_id)->first();
            $question = Question::where('id', $answer->question_id)->first();
            $user = Auth::user();
            $user_data = User_data::where('user_id', $user->id)->first();

            $data = [
                'user_id' => $user->id,
                'content' => $request->input('comment-part-con'),
                'entity_id' => $answer_id,
                'entity_type' => $request->input('entity_type'),
                'to_user_id' => $request->input('to_user'),
                'status' => 1,
            ];
            $comment = Comment::create($data);

            if ($comment) {
                //回答评论数+1
                $answer->increment('comment_count');
                //问题评论数+1
                $question->increment('comment_count');
                //用户评论数+1
                $user_data->increment('comment_count');

                return view('comment.comment_part_item')->with(['comment' => $comment]);
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 保存博客评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function blog_store(Request $request)
    {
        if (Auth::check()) {
            $blog_id = $request->input('blog_id');
            $blog = Blog::where('id', $blog_id)->first();
            $user = Auth::user();
            $user_data = User_data::where('user_id', $user->id)->first();

            $data = [
                'user_id'           =>$user->id,
                'content'           =>$request->input('comment_concent'),
                'entity_id'         =>$blog_id,
                'entity_type'       =>'Blog',
                'to_user_id'        =>$request->input('to_user'),
                'status'            =>1,
            ];
            $comment = Comment::create($data);

            if ($comment) {
                //博客评论数+1
                $blog->increment('comment_count');
                //用户评论数+1
                $user_data->increment('comment_count');

                return $this->success(route('blog.show', ['id' => $blog_id]), '评论成功^_^');
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 保存博客相互评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mutual_blog_store(Request $request)
    {
        if (Auth::check()) {
            $comment_id = $request->input('comment_id');
            $comment = Comment::where('id', $comment_id)->first();
            $blog = Blog::where('id', $comment->entity_id)->first();
            $user = Auth::user();
            $user_data = User_data::where('user_id', $user->id)->first();
            $data = [
                'user_id'           =>$user->id,
                'content'           =>$request->input('comment_part_con'),
                'entity_id'         =>$blog->id,
                'entity_type'       =>'Blog',
                'to_user_id'        =>$comment->user_id,
                'status'            =>1,
            ];
            $comment = Comment::create($data);

            if ($comment) {
                //博客评论数+1
                $blog->increment('comment_count');
                //用户评论数+1
                $user_data->increment('comment_count');

                return view('comment.comment_part_item')->with(['comment' => $comment]);
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 读取回答的评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function answer_comment_show($entity_id, $entity_type)
    {
        $comments = Comment::where('entity_id', $entity_id)->where('entity_type', $entity_type)->orderBy('created_at','asc')->get();
        $answer = Answer::where('id', $entity_id)->first();
        return view('comment.comment_part')->with(['comments' => $comments, 'entity_id' => $entity_id, 'entity_type' => $entity_type, 'answer' => $answer]);
    }

    /**
     * 读取博客的评论
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function blog_comment_show($entity_id, $entity_type)
    {
        $comments = Comment::where('entity_id', $entity_id)->where('entity_type', $entity_type)->orderBy('created_at','asc')->get();
        $answer = Answer::where('id', $entity_id)->first();
        return view('comment.comment_part')->with(['comments' => $comments, 'entity_id' => $entity_id, 'entity_type' => $entity_type, 'answer' => $answer]);
    }
}
