<?php

namespace App\Models;

use App\Models\Core\CoreModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserData extends CoreModel
{
    protected $table = 'user_datas';
    protected $fillable = [
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * 用户信息所属用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
