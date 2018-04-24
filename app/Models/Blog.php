<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;
use Laravel\Scout\Searchable;

class Blog extends Model
{
    use SoftDeletes, Searchable;
    protected $dates = ['delete_at'];

    protected $fillable = [
        'bcategory_id',
        'user_id',
        'title',
        'intro',
        'description',
        'source',
        'source_name',
        'source_link',
        'status',
    ];

    /**
     * 获取模型的索引名称.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'titles_index';
    }

    /**
     * 获取博客对应的用户
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    //最新博客
    public static function newest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $newest = $query->where('status', 1)->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $newest;
    }

    //热门博客
    public static function hottest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $hottest = $query->where('status', 1)->where('view_count', '>', 10)->orderBy('view_count', 'DESC')->orderBy('comment_count', 'DESC')->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $hottest;
    }

    /**
     * 获取博客父评论
     */
    public function parent_comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('depth', 0)->where('status', 1)->orderBy('id', 'DESC');
    }

    /**
     * 获取博客对应的标签
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
