<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
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
     * 获取问答对应的用户
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
    * 获取问答对应的答案
    */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'question_id');
    }

    /**
     * 获取问答对应的标签
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }

    //最新问答
    public static function newest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $newest = $query->where('status', 1)->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $newest;
    }

    //热门问答
    public static function hottest($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $hottest = $query->where('status', 1)->where('view_count', '>', 10)->whereDate('updated_at', date('Y-m-d'))->orderBy('view_count', 'DESC')->orderBy('answer_count', 'DESC')->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $hottest;
    }

    //悬赏问答
    public static function reward($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $reward = $query->where('status', 1)->where('price', '>', 0)->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $reward;
    }

    //待回答问答
    public static function unanswer($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $unanswer = $query->where('status', 1)->where('question_status', 0)->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $unanswer;
    }

    //待解决问答
    public static function unsolve($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $unanswer = $query->where('status', 1)->where('question_status', '<', 2)->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $unanswer;
    }

    //已采纳问答
    public static function adopt($categoryId = 0, $pageSize = 15)
    {
        $query = self::query();
        if( $categoryId > 0 ){
            $query->where('category_id','=',$categoryId);
        }
        $adopt = $query->where('status', 1)->where('question_status', 2)->orderBy('created_at', 'DESC')->paginate($pageSize);
        return $adopt;
    }
}
