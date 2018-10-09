<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Support_opposition;
use App\Models\User_data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * 保存回答评论
     *
     * @param  \Illuminate\Http\Request $request
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
                'content' => $request->input('comment_content'),
                'commentable_id' => $answer_id,
                'commentable_type' => get_class($answer)/*$request->input('commentable_type')*/,
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

                return view('pc.comment.comment_part_item')->with(['comment' => $comment]);
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 保存博客评论
     *
     * @param  \Illuminate\Http\Request $request
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
                'user_id' => $user->id,
                'content' => $request->input('comment_content'),
                'commentable_id' => $blog_id,
                'commentable_type' => get_class($blog),
                'to_user_id' => $request->input('to_user'),
                'status' => 1,
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
            return view('pc.auth.login');
        }
    }

    /**
     * 保存博客相互评论
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function mutual_blog_store(Request $request)
    {
        if (Auth::check()) {
            $comment_id = $request->input('comment_id');
            $comment = Comment::where('id', $comment_id)->first();
            $blog = Blog::where('id', $comment->commentable_id)->first();
            $user = Auth::user();
            $user_data = User_data::where('user_id', $user->id)->first();
            $data = [
                'user_id' => $user->id,
                'content' => $request->input('comment_child'),
                'commentable_id' => $blog->id,
                'commentable_type' => get_class($blog),
                'to_user_id' => $request->input('to_user'),
                'status' => 1,
            ];
            $mutual_comment = Comment::create($data);

            if ($request->get('parent_id') > 0) {
                $mutual_comment->makeChildOf(Comment::findOrFail($request->get('parent_id')));
            }

            if ($mutual_comment) {
                //博客评论数+1
                $blog->increment('comment_count');
                //用户评论数+1
                $user_data->increment('comment_count');

                return view('pc.comment.comment_child_blog')->with(['mutual_comment' => $mutual_comment]);
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 读取回答的评论
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($commentable_id, $commentable_type)
    {
        $answer = Answer::where('id', $commentable_id)->first();
        $comments = Comment::where('commentable_id', $commentable_id)->where('commentable_type', get_class($answer))->orderBy('created_at', 'asc')->get();

        return view('pc.comment.comment_part')->with(['comments' => $comments, 'commentable_id' => $commentable_id, 'commentable_type' => $commentable_type, 'answer' => $answer]);
    }

    /**
     * 博客评论支持
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function support($comment_id)
    {
        if (Auth::check()) {
            $comment = Comment::where('id', $comment_id)->first();
            $supp_oppo = Support_opposition::where('user_id', Auth::user()->id)->where('sup_opp_able_id', $comment_id)->where('sup_opp_able_type', get_class($comment))->where('sup_opp_mode', 'support')->first();
            $user_data = User_data::where('user_id', $comment->user_id)->first();   //评论所属用户
            $curr_user_data = User_data::where('user_id', Auth::user()->id)->first();   //当前用户
            //存在支持记录，则属于取消支持
            if ($supp_oppo) {
                $bool = $supp_oppo->delete();
                if ($bool == true) {
                    //评论支持数-1
                    $comment->decrement('support_count');
                    //用户被支持数-1
                    $user_data->decrement('supported_count');
                    //当前用户支持数-1
                    $curr_user_data->decrement('support_count');
                }

                return response('unsupport');
            } else {
                //不存在支持记录，则属于支持
                $data = [
                    'user_id' => Auth::user()->id,
                    'sup_opp_able_id' => $comment_id,
                    'sup_opp_able_type' => get_class($comment),
                    'sup_opp_mode' => 'support',
                ];
                $s_o = Support_opposition::create($data);
                if ($s_o) {
                    //评论支持数-1
                    $comment->increment('support_count');
                    //用户被支持数-1
                    $user_data->increment('supported_count');
                    //当前用户支持数-1
                    $curr_user_data->increment('support_count');

                    return response('support');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 博客评论编辑
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $comment_id)
    {
        $comment = Comment::where('id', $comment_id)->first();
        $content = $request->input('edit_comment_con');
        $edit_content = '<p>' . $content . '</p>';
        $comment->content = $edit_content;
        $bool = $comment->save();

        if ($bool == true) {
            return response($request->input('edit_comment_con'));
        }
    }

    /**
     * 博客评论删除
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment_id)
    {
        $comment = Comment::where('id', $comment_id)->first();
        $user_data = User_data::where('user_id', $comment->user_id)->first();
        $comment->delete();
        if ($comment->trashed()) {
            $user_data->decrement('comment_count');

            return $this->jsonResult(710);
        } else {
            return $this->jsonResult(711);
        }
    }
}
