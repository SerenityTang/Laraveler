<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = [
        'tcategory_id',
        'name',
        'logo',
        'description',
        'status',
        'attention_count',
        'created_at',
        'updated_at'
    ];

    /**
     * 获取所有分配该标签的问答
     */
    public function questions()
    {
        return $this->morphedByMany('App\Models\Question', 'taggable');
    }

    /**
     * 获取分配该标签的所有博客
     */
    public function blogs()
    {
        return $this->morphedByMany('App\Models\Blog', 'taggable');
    }
}
