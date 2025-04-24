<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    #post belongs to a user
    #to get the owner of the post
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    #To get all the categories for a post but only IDs
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    # to get all comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    #Post has many likes , to get all the likes of a post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    #Returns TRUE if the Auth user already liked the post
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }

    // Count uncategorized posts for ADMIN Categories page
    public static function countUncategorizedPosts()
    {
        // return self::whereDoesntHave('categoryPost')->count();
        return self::doesntHave('categoryPost')->count();
    }

}
