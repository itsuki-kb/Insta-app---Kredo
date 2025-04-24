<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // In Profile page, only the owner and the followers
    // can see the posts/followers, send messages.
    public function viewProfile(User $viewer, User $profileOwner)
    {
        // if public user, everyone is allowed
        if (!$profileOwner->private) return true;

        // the owner of the page is allowed
        if ($viewer->id === $profileOwner->id) return true;

        // if Auth user is following the user AND the request is accepted, OK
        if ($profileOwner->isFollowed()){
            if ($profileOwner->hasPendingRequest()){
                // if the follow is on request, NOT allowed
                return false;
            } else {
                // if the follow is accepted, allowed
                return true;
            }
        };
    }

    public function isActiveUser(User $authUser, User $user)
    {
        // return TRUE if not soft deleted
        return !$user->trashed();
    }
}
