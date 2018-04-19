<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * 搜索框智能提示
     * @param int $id 活动id
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function search_scout(Request $request, $filter = 'all')
    {
        $keyword = $request->input('search-text');
        if ($keyword != null) {
            //搜索问答
            $ques_searchs = Question::search($keyword)->get();
            //搜索博客
            $blog_searchs = Blog::search($keyword)->get();
            return view('pc.search.show')->with(['filter' => $filter, 'keyword' => $keyword, 'ques_searchs' => $ques_searchs, 'blog_searchs' => $blog_searchs]);
        } else {
            $keyword = $_GET['q'];
            switch ($filter) {
                case 'all':
                    //搜索问答
                    $ques_searchs = Question::search($keyword)->get();
                    //搜索博客
                    $blog_searchs = Blog::search($keyword)->get();

                    return view('pc.search.show')->with(['filter' => $filter, 'keyword' => $keyword, 'ques_searchs' => $ques_searchs, 'blog_searchs' => $blog_searchs]);
                case 'question':
                    //搜索问答
                    $ques_searchs = Question::search($keyword)->get();

                    return view('pc.search.show')->with(['filter' => $filter, 'keyword' => $keyword, 'ques_searchs' => $ques_searchs]);
                case 'blog':
                    //搜索博客
                    $blog_searchs = Blog::search($keyword)->get();

                    return view('pc.search.show')->with(['filter' => $filter, 'keyword' => $keyword, 'blog_searchs' => $blog_searchs]);
            }
        }
    }

    /**
     * 自动完成标签
     * @param string $QUERY 查询词(逗号触发添加标签)
     * @author nanyi<nanyi@duxingshe.cn> by 2017-08-26 10:56:15
     * @return mixed
     */
    public function tags($QUERY = null)
    {
        $word = $QUERY;
        if (strpos($word, ' ') !== false) {
            $word = str_replace([' ', ',', '，'], '', trim($word)); // 清除空格、中英文逗号
            if ($word) {
                if (Auth::check()) {
                    $user = Auth::user();
                    try {
                        $querys = Tag::where('name', $word)->first();
                        if (!$querys || !$querys->id) {
                            $data = [
                                'tid'         => 0,
                                'user_id'     => $user->id,
                                'name'        => $word,
                                'avatar'      => '',
                                'description' => '',
                                'created_at'  => Carbon::now()->toDateTimeString(),
                                'updated_at'  => Carbon::now()->toDateTimeString()
                            ];
                            Tag::create($data);
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
        }
        if ($word) {
            $querys = Tag::where('name', 'LIKE', "%$word%")->orderByRaw('LENGTH(`name`),INSTR(`name`, \'' . $word . '\'),name')->get();
        } else {
            $querys = Tag::orderByRaw('name')->get();
        }
        $tags = array();
        foreach ($querys as $index => $tag) {
            $taxonomy = $tag->taxonomy;
            $tags[$index] = [
                'value'    => $tag->id,
                'text'     => $tag->name,
                'category' => $taxonomy ? $taxonomy->name : '',
            ];
        }

        return Response::json($tags);
    }
}
