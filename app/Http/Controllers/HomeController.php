<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    private $post;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts      = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        // Instead of below, created ViewServiceProvider
        // $notifications   = $this->chat->countUnreadMessages();

        $follow_requests = $this->getFollowRequests();

        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users)
            ->with('follow_requests', $follow_requests);
    }

    # get the posts of the auth user and the posts of the following users
    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->with('user')->get(); // ← N+1対策でwith('user')追加
        $home_posts = $all_posts->filter(function ($post) {
            return Gate::allows('viewProfile', [$post->user, Auth::user()]);
        })->values(); // ← index振り直し

        return $home_posts;
    }

    # get suggested users
    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if (!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 5);
        // array_slice(x, y, z)
        // x --- array
        // y --- offset/starting index
        // z --- length/how many
    }

    public function showSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if (!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return view('users.suggestions')
            ->with('suggested_users', $suggested_users);
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();
                                    // Like operator in SQL is used for pattertn matching
                                    // % matched anywhere in the name
        return view('users.search')
            ->with('users', $users)
            ->with('search', $request->search);
    }

    # get follow requests
    private function getFollowRequests()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        return $user->followers()
                    ->where('on_request', 1)
                    ->limit(5)
                    ->get();
    }

    public function showFollowRequests()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        $follow_requests = $user->followers()
                                ->where('on_request', 1)
                                ->get();

        return view('users.requests')
            ->with('follow_requests', $follow_requests);
    }

}
