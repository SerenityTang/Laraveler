<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\Tag;
use App\Models\User_data;
use Illuminate\Support\Facades\Auth;
use Browser;

class TagController extends Controller
{
    /**
     * 标签首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::where('status', 1)->get();

        if (Browser::isMobile()) {
            return view('mobile.tag.index')->with(['tags' => $tags]);
        }
        return view('pc.tag.index')->with(['tags' => $tags]);
    }

    /**
     * 标签内容列表
     *
     * @return \Illuminate\Http\Response
     */
    public function tag_show($tag_id)
    {
        $tag = Tag::where('id', $tag_id)->where('status', 1)->first();
        //获取此标签下的问答
        $questions = $tag->questions;
        //获取此标签下的博客
        $blogs = $tag->blogs;

        return view('pc.tag.tag_show')->with(['tag' => $tag, 'questions' => $questions, 'blogs' => $blogs]);
    }

    /**
     * 关注标签
     *
     * @return \Illuminate\Http\Response
     */
    public function attention($tag_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $tag = Tag::where('id', $tag_id)->first();
            $user_data = User_data::where('user_id', $user->id)->first();
            $attention = Attention::where('user_id', $user->id)->where('entityable_id', $tag_id)->where('entityable_type', get_class($tag))->first();
            //如果此用户关注过此标签，则属于取消关注
            if ($attention) {
                $attention_bool = $attention->delete();
                if ($attention_bool == true) {
                    $tag->decrement('attention_count');     //关注数-1
                    $user_data->decrement('atten_count'); //当前用户关注数-1

                    return response('unattention');
                }
            } else {
                //如果此用户无关注过此标签，则属于关注
                $data = [
                    'user_id' => $user->id,
                    'entityable_id' => $tag_id,
                    'entityable_type' => get_class($tag)
                ];

                $new_attention = Attention::create($data);
                if ($new_attention) {
                    $tag->increment('attention_count');     //关注数+1
                    $user_data->increment('atten_count'); //当前用户关注数+1

                    return response('attention');
                }
            }
        } else {
            return view('pc.auth.login');
        }
    }
}
