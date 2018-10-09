<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Node
{
    protected $table = 'comments';

    // 'parent_id' column name
    protected $parentColumn = 'parent_id';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rgt';

    // 'depth' column name
    protected $depthColumn = 'depth';

    // guard attributes from mass-assignment
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /*protected $fillable = [
        'user_id',
        'content',
        'entity_id',
        'entity_type',
        'to_user_id',
        'status',
    ];*/

    /**
     * 获取评论所属用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * 获取评论被回复用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toUser()
    {
        return $this->belongsTo('App\User', 'to_user_id');
    }

    /**
     * Determine if the comment has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all of comment's children.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getChildren($columns = ['*'])
    {
        return $this->children()->get($columns);
    }
}
