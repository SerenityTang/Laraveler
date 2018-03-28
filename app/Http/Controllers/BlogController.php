<?php

namespace App\Http\Controllers;

use App\Models\Blog;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = 'newest')
    {
        $blog = new Blog();
        $blogs = call_user_func([$blog, $filter]);

        return view('blog.index')->with(['blogs' => $blogs, 'filter' => $filter]);
    }

    /**
     * Show the form for creating a new resource.
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * 删除/舍弃博客
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
            $user_data->decrement('question_count');

            return $this->jsonResult(701);
        }else{
            return $this->jsonResult(702);
        }
    }
}
