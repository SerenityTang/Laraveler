<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Taggable;
use App\Models\User_data;
use Illuminate\Http\Request;
use Mail;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mail()
    {
        /*$user = User::first();
        $user->notify(new VerificationCode());*/
        Mail::raw('Serenity 邮件测试', function ($message) {
            $message->to('serenity_tang@sina.com');
        });
        /*$mailer::send('auth.login', ['username' => $user], function ($message) use ($email){
            $message->from('13676225868@163.com', 'Serenity');
            $message->to('1060684139@qq.com');
        });*/
    }

    /**
     * 博客首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = 'newest')
    {
        $blog = new Blog();
        $blogs = call_user_func([$blog, $filter]);

        //置顶博客
        $stick_blogs = Blog::where('status', 1)->where('sticky', 1)->get();

        //推荐博客
        $promote_blogs = Blog::where('status', 1)->where('promote', 1)->get();

        //问答热门标签
        $taggables = Taggable::where('taggable_type', get_class($blog))->get();
        $tags = array();
        foreach ($taggables as $taggable) {
            $tag = Tag::where('id', $taggable->tag_id)->first();
            array_push($tags, $tag);
            $hot_tags = array_unique($tags);
        }

        if (!isset($hot_tags)) {
            return view('blog.index')->with(['blogs' => $blogs, 'filter' => $filter, 'stick_blogs' => $stick_blogs, 'promote_blogs' => $promote_blogs]);
        }
        return view('blog.index')->with(['blogs' => $blogs, 'filter' => $filter, 'stick_blogs' => $stick_blogs, 'promote_blogs' => $promote_blogs, 'hot_tags' => $hot_tags]);
    }

    /**
     * 创建博客
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * 发布博客
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $data = [
                'bcategory_id'      =>$request->input('bcategory_id', 0),
                'user_id'           =>$user->id,
                'title'             =>$request->input('blog_title'),
                'intro'             =>$request->input('blog_intro'),
                'description'       =>$request->input('description'),
                'source'            =>$request->input('source', 0),
                'source_name'       =>$request->input('source_name'),
                'source_link'       =>$request->input('source_link'),
                'status'            =>1,
            ];
            $blog = Blog::create($data);

            //判断是否发布成功
            if ($blog) {
                $user_data = User_data::where('user_id', $user->id)->first();
                $user_data->increment('article_count');

                return $this->success('/blog', '发布博客成功^_^');
            } else {
                return $this->error('/blog', '发布博客失败^_^');
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 保存博客草稿
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_draft(Request $request)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $data = [
                'bcategory_id'      =>$request->input('bcategory_id', 0),
                'user_id'           =>$user->id,
                'title'             =>$request->input('blog_title'),
                'intro'             =>$request->input('blog_intro'),
                'description'       =>$request->input('description'),
                'source'            =>$request->input('source', 0),
                'source_name'       =>$request->input('source_name'),
                'source_link'       =>$request->input('source_link'),
                'status'            =>2,
            ];
            $blog = Blog::create($data);

            //判断是否发布成功
            if ($blog) {
                return $this->jsonResult(501, '保存草稿成功，请前往个人主页查看^_^');
            } else {
                return $this->error('/blog', '保存草稿失败^_^');
            }
        } else {
            return view('auth.login');
        }
    }

    /**
     * 博客内容页
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::where('id', $id)->first();
        $comments = Comment::where('entity_id', $blog->id)->where('entity_type', 'Blog')->whereNull('to_user_id')->orderBy('created_at','asc')->get();


        return view('blog.show')->with(['blog' => $blog, 'comments' => $comments, 'mutual_comments' => $mutual_comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * 删除博客
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::where('id', $id)->first();
        $user_data = User_data::where('user_id', $blog->user_id)->first();
        $blog->delete();
        if($blog->trashed()){
            $user_data->decrement('article_count');

            return $this->jsonResult(706);
        }else{
            return $this->jsonResult(707);
        }
    }

    /**
     * 舍弃博客
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function abandon($id)
    {
        $blog = Blog::where('id', $id)->first();

        $blog->delete();
        if($blog->trashed()){
            return $this->jsonResult(708);
        }else{
            return $this->jsonResult(709);
        }
    }
}
