<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    # comment belongs to a user
    # to get the owner info of the comment
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }


}
