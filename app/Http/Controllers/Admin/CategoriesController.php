<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    #index() - View Admin: categories page
    public function index()
    {
        // withTrashed() - include the soft deleted records in the query's results
        $all_categories = $this->category->latest()->get();

        // count uncategorized posts
        $uncategorized_posts = Post::countUncategorizedPosts();

        return view('admin.categories.index')
            ->with('all_categories', $all_categories)
            ->with('uncategorized_posts', $uncategorized_posts);
    }

    #create
    public function store(Request $request)
    {
        $request->validate([
            'new_category' => 'required|max:50|unique:categories,name'
        ]);

        $formatted_category = ucfirst(strtolower(($request->new_category)));

        $this->category->name = $formatted_category;
        $this->category->save();

        return redirect()->back();

    }


    # update
    public function update(Request $request, $id)
    {
        $category = $this->category->findOrFail($id);

        $category->name = $request->name;

        $category->save();

        return redirect()->back();
    }

    #destroy()
    public function destroy($id)
    {
        $category = $this->category->findOrFail($id);

        $category->delete();

        return redirect()->back();
    }


}
