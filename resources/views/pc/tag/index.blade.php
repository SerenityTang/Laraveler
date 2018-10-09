@extends('pc.layouts.app')
@section('title')
    标签云 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/tag/default.css') }}">
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="page-header title-header">
                <h2 class="title">标签</h2>
            </div>
            <p class="title-header-desc">标签在 Laraveler
                是用以表示一个问答、博客的标签，是一种更为灵活、方便的网站平台内容分类方式，您可以在发布内容的时候添加一个或多个标签，这样就可以在接下来查找中以最快的速度，便捷的方式定位并找到心仪的内容。</p>
        </div>
        <div class="row tag-content">
            @foreach($tags as $tag)
                <span class="tag-item">
                    <a href="{{ url('/tag/tag_show/'. $tag->id) }}">
                        {{ $tag->name }}
                        <em>{{ \App\Helpers\Helpers::getByTag($tag->id) }}</em>
                    </a>
                </span>
            @endforeach
        </div>
    </div>
@stop

@section('footer')

@stop