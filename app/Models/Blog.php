<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Blog extends CoreModel
{
    use Searchable;

    protected $table = 'blogs';
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * 最新博客
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function newest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('bcategory_id', $categoryId);
        }
        $newest = $query->where('status', 1)->orderByDesc('created_at')->paginate($pageSize);

        return $newest;
    }

    /**
     * 热门博客
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function hottest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('bcategory_id', $categoryId);
        }
        $hottest = $query->where('status', 1)->where('view_count', '>', 10)->orderByDesc('view_count')->orderByDesc('comment_count')->orderByDesc('created_at')->paginate($pageSize);

        return $hottest;
    }

    /**
     * 获取博客父评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function parent_comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('depth', 0)->where('status', 1)->orderByDesc('id');
    }

    /**
     * 获取博客对应的标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
