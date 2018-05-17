<?php

namespace App\Http\Controllers;

use App\Events\BlogCreditEvent;
use App\Events\BlogOperationCreditEvent;
use App\Events\BlogViewEvent;
use App\Models\Blog;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Support_opposition;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\User_data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Browser;

class BlogController extends Controller
{
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

        if (Browser::isMobile()) {
            $blog = new Blog();
            $newest_blogs = call_user_func([$blog, 'newest']);
            $hottest_blogs = call_user_func([$blog, 'hottest']);

            return view('mobile.blog.index')->with(['newest_blogs' => $newest_blogs, 'hottest_blogs' => $hottest_blogs]);
        }

        if (!isset($hot_tags)) {
            return view('pc.blog.index')->with(['blogs' => $blogs, 'filter' => $filter, 'stick_blogs' => $stick_blogs, 'promote_blogs' => $promote_blogs]);
        }

        return view('pc.blog.index')->with(['blogs' => $blogs, 'filter' => $filter, 'stick_blogs' => $stick_blogs, 'promote_blogs' => $promote_blogs, 'hot_tags' => $hot_tags]);
    }

    /**
     * 创建博客
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::where('status', 1)->get();

        return view('pc.blog.create')->with(['tags' => $tags]);
    }

    /**
     * 发布博客
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'bcategory_id'      =>$request->input('bcategory_id', 0),
                'user_id'           =>$user->id,
                'title'             =>$request->input('blog_title'),
                'intro'             =>$request->input('blog_intro'),
                'description'       =>$request->input('desc'),
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

                //绑定标签
                $tags = explode(',', $request->input('tags'));
                foreach ($tags as $tag) {
                    $taggables = [
                        'tag_id'            =>$tag,
                        'taggable_id'     =>$blog->id,
                        'taggable_type'   =>get_class($blog),
                    ];
                    $taggable = Taggable::create($taggables);
                }
                if ($taggable) {
                    //触发添加积分事件
                    Event::fire(new BlogCreditEvent($user));

                    return $this->success('/blog', '发布博客成功^_^');
                } else {
                    return $this->error('/blog', '发布博客失败，未绑定标签^_^');
                }
            } else {
                return $this->error('/blog', '发布博客失败^_^');
            }
        } else {
            return view('pc.auth.login');
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
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'bcategory_id'      =>$request->input('bcategory_id', 0),
                'user_id'           =>$user->id,
                'title'             =>$request->input('blog_title'),
                'intro'             =>$request->input('blog_intro'),
                'description'       =>$request->input('desc'),
                'source'            =>$request->input('source', 0),
                'source_name'       =>$request->input('source_name'),
                'source_link'       =>$request->input('source_link'),
                'status'            =>2,
            ];
            $blog = Blog::create($data);

            //判断是否发布成功
            if ($blog) {
                //绑定标签
                $tags = explode(',', $request->input('tags'));
                foreach ($tags as $tag) {
                    $taggables = [
                        'tag_id'          =>$tag,
                        'taggable_id'     =>$blog->id,
                        'taggable_type'   =>get_class($blog),
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
     * 博客内容页
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::where('id', $id)->first();

        //博客浏览量统计
        Event::fire(new BlogViewEvent($blog));

        //其它博客
        $other_blogs = Blog::where('user_id', $blog->user_id)->where('status', 1)->get();
        foreach ($other_blogs as $other_blogkey => $other_blogvalue) {
            if ($other_blogvalue->id == $id) {
                unset($other_blogs[$other_blogkey]);
            }
        }

        //相关博客
        $tag_id = $blog->tags()->pluck('tag_id');
        $correlation_blogs = $blog->whereHas('tags', function ($query) use ($tag_id) {
            $query->whereIn('tag_id', $tag_id);
        })->where('status', 1)->orderBy('created_at','DESC')->take(8)->get();

        return view('pc.blog.show')->with(['blog' => $blog, 'other_blogs' => $other_blogs, 'correlation_blogs' => $correlation_blogs]);
    }

    /**
     * 博客评论分类
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sort_show($id, $sort)
    {
        $blog = Blog::where('id', $id)->first();

        if ($sort === 'default') {
            $comments = Comment::where('commentable_id', $id)->where('commentable_type', get_class($blog))->where('status', 1)->where('depth', 0)->get();
        } else if ($sort === 'time') {
            $comments = Comment::where('commentable_id', $id)->where('commentable_type', get_class($blog))->where('status', 1)->where('depth', 0)->orderBy('created_at', 'DESC')->get();
        } else if ($sort === 'support') {
            $comments = Comment::where('commentable_id', $id)->where('commentable_type', get_class($blog))->where('status', 1)->where('depth', 0)->orderBy('support_count', 'DESC')->get();
        }

        return view('pc.blog.parts.blog_comment')->with(['comments' => $comments]);
    }

    /**
     * 展示博客编辑页
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_edit($id)
    {
        $blog = Blog::where('id', $id)->first();
        $tags = Tag::where('status', 1)->get();     //获取tag展示在下拉列表
        //获取此问答绑定的标签
        $bound_tags = [];
        $taggables = Taggable::where('taggable_id', $blog->id)->where('taggable_type', get_class($blog))->get();
        foreach ($taggables as $taggable) {
            $tag = Tag::where('id', $taggable->tag_id)->first();
            array_push($bound_tags, $tag);
        }

        if (Auth::check()) {
            $user_data = User_data::where('user_id', Auth::user()->id)->first();
        } else {
            return view('pc.auth.login');
        }

        return view('pc.blog.edit')->with(['blog' => $blog, 'user_data' => $user_data, 'tags' => $tags, 'bound_tags' => $bound_tags]);
    }

    /**
     * 保存修改博客
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (Auth::check()) {
            $blog = Blog::where('id', $id)->first();
            $ori_status = $blog->status;
            $blog->bcategory_id = $request->input('bcategory_id');
            $blog->title = $request->input('blog_title');
            $blog->intro = $request->input('blog_intro');
            $blog->description = $request->input('desc');
            $blog->source = $request->input('source');
            $blog->source_name = $request->input('source_name');
            $blog->source_link = $request->input('source_link');
            $blog->status = 1;
            $bool = $blog->save();

            if ($bool == true) {
                if ($ori_status == 2) {
                    $user_data = User_data::where('user_id', Auth::user()->id)->first();
                    $user_data->increment('article_count');

                    return $this->success(route('blog.show', $id), '您的博客草稿以博客方式发布成功^_^');
                } else {
                    return $this->success(route('blog.show', $id), '博客编辑成功^_^');
                }
            }
        } else {
            return view('pc.auth.login');
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

    /**
     * 点赞博客
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $blog = Blog::where('id', $id)->first();
            $curr_user_data = User_data::where('user_id', $user->id)->first();
            $user_data = User_data::where('user_id', $blog->user_id)->first();
            $supp_oppo = Support_opposition::where('user_id', $user->id)->where('sup_opp_able_id', $id)->where('sup_opp_able_type', get_class($blog))->where('sup_opp_mode', 'like')->first();
            $blog_user = $blog->user;

            //如果此用户点赞过此博客，则属于取消点赞
            if ($supp_oppo) {
                $bool = $supp_oppo->delete();
                if ($bool == true) {
                    $blog->decrement('like_count');     //点赞数-1
                    $curr_user_data->decrement('support_count'); //当前用户点赞数-1
                    $user_data->decrement('supported_count'); //回答所属用户被点赞数-1

                    //取消点赞，扣取点赞添加的积分
                    Event::fire(new BlogOperationCreditEvent($blog_user, 'like', 'no'));

                    return response('unlike');
                }
            } else {
                //如果此用户无点赞过此博客，则属于点赞
                $data = [
                    'user_id'           =>$user->id,
                    'sup_opp_able_id'   =>$id,
                    'sup_opp_able_type' =>get_class($blog),
                    'sup_opp_mode'      =>'like',
                ];

                $s_o = Support_opposition::create($data);
                if ($s_o) {
                    $blog->increment('like_count');     //点赞数+1
                    $curr_user_data->increment('support_count'); //当前用户点赞数+1
                    $user_data->increment('supported_count'); //回答所属用户被点赞数+1

                    //点赞，添加点赞积分
                    Event::fire(new BlogOperationCreditEvent($blog_user, 'like', 'yes'));

                    return response('like');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }

    /**
     * 收藏博客
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function favorite($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $blog = Blog::where('id', $id)->first();
            $curr_user_data = User_data::where('user_id', $user->id)->first();
            $user_data = User_data::where('user_id', $blog->user_id)->first();
            $collection = Collection::where('user_id', $user->id)->where('entityable_id', $id)->where('entityable_type', get_class($blog))->first();
            $blog_user = $blog->user;

            //如果此用户收藏过此博客，则属于取消收藏
            if ($collection) {
                $bool = $collection->delete();
                if ($bool == true) {
                    $blog->decrement('favorite_count');     //收藏数-1
                    $curr_user_data->decrement('collection_count'); //当前用户收藏数-1
                    $user_data->decrement('collectioned_count'); //回答所属用户被收藏数-1

                    //取消收藏，扣取收藏添加的积分
                    Event::fire(new BlogOperationCreditEvent($blog_user, 'favorite', 'no'));

                    return response('unfavorite');
                }
            } else {
                //如果此用户无收藏过此博客，则属于收藏
                $data = [
                    'user_id'           =>$user->id,
                    'entityable_id'     =>$id,
                    'entityable_type'   =>get_class($blog),
                ];

                $coll = Collection::create($data);
                if ($coll) {
                    $blog->increment('favorite_count');     //收藏数+1
                    $curr_user_data->increment('collection_count'); //当前用户收藏数+1
                    $user_data->increment('collectioned_count'); //回答所属用户被收藏数+1

                    //收藏，添加收藏积分
                    Event::fire(new BlogOperationCreditEvent($blog_user, 'favorite', 'yes'));

                    return response('favorite');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }
}
