<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # A user has many posts
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    #user has many followers
    // 対象ユーザー(following_id)の、全フォロワーIDを取得
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    // To get all the following users
    // 対象ユーザー（follower_id）がフォローしている全ユーザーを取得
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    // Will return TRUE if the Auth user is following a user
    public function isFollowed()
    {
        return $this->followers()
                    ->where('follower_id', Auth::user()->id)
                    ->exists();
    }

    // Switch if the user is private or not(0=public, 1=private)
    public function togglePrivate()
    {
        $this->private = !$this->private;
        return $this->private;
    }

    // Check if the user is public or private --- used in Follow Controller
    public function isPrivate()
    {
        if($this->private == 1) {
            return true;
        } else {
            return false;
        };
    }

    // Will return TRUE if the Auth user has sent a follow request (still pending)
    public function hasPendingRequest()
    {
        // if there is a pending request, return TRUE
        return $this->followers()
                    ->where('follower_id', Auth::id())
                    ->where('on_request', 1)
                    ->exists();
    }

    // Count follow requests to display on the nav bar
    public static function countFollowRequests()
    {
        $user = self::where('id', Auth::user()->id)->first();

        $follow_requests = $user->followers()
                                ->where('on_request', 1)
                                ->count();

        return $follow_requests;
    }

}
