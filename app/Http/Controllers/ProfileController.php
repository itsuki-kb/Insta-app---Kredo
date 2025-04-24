<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // show() - vie shoe.blade.php / profile page
    public function show($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.show')
            ->with('user', $user);
    }

    //edit()
    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.profile.edit')
            ->with('user', $user);
    }

    //update()
    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'introduction' => 'max:100',
            'avatar' => 'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->introduction){
            $user->introduction = $request->introduction;
        }

        if($request->avatar){
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        if ($request->has('private')){
            // if the box is checked AND it's not a private user, make it private
            if ($user->private == 0){
                $user->private = $user->togglePrivate();  // defined in User model
            }
        } else {
            // if the box is NOT checked AND it's a private user, make it public
            if ($user->private == 1){
                $user->private = $user->togglePrivate();
            }
        }

        $user->save();

        return redirect()->route('profile.show', $user->id);
    }

    //followers() - view the followers page of a user in user-show page
    public function followers($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.followers')
            ->with('user', $user);
    }

    // followers() view the following user page
    public function following($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.following')
            ->with('user', $user);
    }

    public function showNotifications($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.notifications')
            ->with('user', $user);
    }

}
