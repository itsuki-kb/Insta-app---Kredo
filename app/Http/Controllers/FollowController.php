<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    private $follow;
    private $user;

    public function __construct(Follow $follow, User $user)
    {
        $this->follow = $follow;
        $this->user   = $user;
    }

    // store() follow the user
    public function store($user_id)
    {
        $this->follow->follower_id  = Auth::user()->id;
        $this->follow->following_id = $user_id;

        // Check if the user is public or private
        $followed_user = $this->user->findOrFail($user_id);
        if ($followed_user->isPrivate()){
            $this->follow->on_request = 1;
        }

        $this->follow->save();

        return redirect()->back();
    }

    // destroy() - unfollow user
    public function destroy($user_id)
    {
        $this->follow
             ->where('follower_id', Auth::user()->id)
             ->where('following_id', $user_id)
             ->delete();

        return redirect()->back();
    }

    // accept follow request
    public function accept($user_id)
    {
        $accept_request = $this->follow
            ->where('follower_id', $user_id)
            ->where('following_id', Auth::user()->id);

        $accept_request->update(['on_request' => 0]);
        return redirect()->back();
    }

    // decline follow request
    public function decline($user_id)
    {
        $this->follow
             ->where('following_id', Auth::user()->id)
             ->where('follower_id', $user_id)
             ->delete();

        return redirect()->back();
    }

}
