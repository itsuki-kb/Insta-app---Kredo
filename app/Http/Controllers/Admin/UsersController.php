<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    #index() - View Admin: users page
    public function index()
    {
        // withTrashed() - include the soft deleted records in the query's results
        $all_users = $this->user->withTrashed()->latest()->paginate(5);

        return view('admin.users.index')
            ->with('all_users', $all_users);
    }

    # deactivate() - soft delete the user
    public function deactivate($id)
    {
        $this->user->destroy($id);

        return redirect()->back();
    }

    #activate()
    public function activate($id)
    {
        // onlyTrashed() - retrieves soft deleted records only.
        // restore() - this will 'un-delete' the soft deleted model.
        //             this will set the deleted_at column to null.

        $this->user->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

    #search
    public function search(Request $request)
    {
        $all_users = $this->user->withTrashed()
            ->where('name', 'like', '%' . $request->search . '%')
            ->paginate(4);

        return view('admin.users.search')
            ->with('all_users', $all_users)
            ->with('search', $request->search);
    }

}
