<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Question extends CoreModel
{
    use Searchable;
    protected $table = 'questions';
    protected $dates = ['delete_at'];
    protected $fillable = [
        'user_id',
        'qcategory_id',
        'title',
        'description',
        'price',
        'device',
        'question_status',
        'status',
        'created_at',
        'updated_at'
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
     * 获取问答对应的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * 获取问答对应的答案
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'question_id', 'id');
    }

    /**
     * 获取问答对应的标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }

    /**
     * 最新问答
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function newest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if ($categoryId > 0) {
            $query->where('qcategory_id', $categoryId);
        }
        $newest = $query->where('status', 1)->orderByDesc('created_at')->paginate($pageSize);

        return $newest;
    }

    /**
     * 热门问答
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function hottest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if ($categoryId > 0) {
            $query->where('qcategory_id', $categoryId);
        }
        $hottest = $query->where('status', 1)->where('view_count', '>', 10)/*->whereDate('updated_at', date('Y-m-d'))*/
        ->orderByDesc('view_count')->orderByDesc('answer_count')->orderByDesc('created_at')->paginate($pageSize);

        return $hottest;
    }

    /**
     * 悬赏问答
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function reward($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if ($categoryId > 0) {
            $query->where('qcategory_id', $categoryId);
        }
        $reward = $query->where('status', 1)->where('price', '>', 0)->orderByDesc('created_at')->paginate($pageSize);

        return $reward;
    }

    /**
     * 待回答问答
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function unanswer($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if ($categoryId > 0) {
            $query->where('qcategory_id', $categoryId);
        }
        $unanswer = $query->where('status', 1)->where('question_status', 0)->orderByDesc('created_at')->paginate($pageSize);

        return $unanswer;
    }

    /**
     * 待解决问答
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function unsolve($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if ($categoryId > 0) {
            $query->where('qcategory_id', $categoryId);
        }
        $unanswer = $query->where('status', 1)->where('question_status', '<', 2)->orderByDesc('created_at')->paginate($pageSize);

        return $unanswer;
    }

    /**
     * 已采纳问答
     *
     * @param int $categoryId
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function adopt($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if ($categoryId > 0) {
            $query->where('qcategory_id', $categoryId);
        }
        $adopt = $query->where('status', 1)->where('question_status', 2)->orderByDesc('created_at')->paginate($pageSize);

        return $adopt;
    }
}
