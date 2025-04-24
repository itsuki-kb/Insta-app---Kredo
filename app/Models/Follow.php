<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    // To avoid adding timestamps in create()
    public $timestamps = false;

    // To get the info of a follower like name, email...
    //　対象ユーザー（follower_id）のデータを取得する
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }

    // To get the info of the following user
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }

}
