<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    # create() - view the Create Post Page
    public function create()
    {
        $all_categories = $this->category->all(); //retrieve all the categories

        return view('users.posts.create')
            ->with('all_categories', $all_categories);
    }

    #store()
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:1048'
                                #multipurpose Internet Mail Extensions
        ]);

        #save the post
        $this->post->user_id        = Auth::user()->id;
        $this->post->image          = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
                                    # Syntax: data:[content]/[type];base64,
        $this->post->description    = $request->description;
        $this->post->save();

        #save the post categories to the category_post pivot table
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
            /* 2D assoc array
                category_post = [
                    ['category_id' => 1],
                    ['category_id' => 2],
                    ['category_id' => 3],
                ]
            */

        }
        // createMany() to store 2D assoc array, while create() only accepts assoc array
        $this->post->categoryPost()->createMany($category_post);
            /* multiple data will be stored in category_post table!
                ['post_id' => 1, 'category_id' => 1],
                ['post_id' => 1, 'category_id' => 1],
                ['post_id' => 1, 'category_id' => 1],
            */
        #Go back to homepage
        return redirect()->route('index');
    }

    //show - view show post page
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
            ->with('post', $post);
    }

    //edit - edit the post
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        //if the user is not the owner of the post, redirect to homepage
        if(Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        $all_categories = $this->category->all(); //retrieves all categories

        # Get all the category IDs of this post, Save in array
        $selected_categories = [];
        foreach($post->categoryPost as $category_post){
            $selected_categories[] = $category_post->category_id;
            /*
                $selected_categories = [
                    [1],
                    [2],
                    [3]
                ]
            */
        }

        return view('users.posts.edit')
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);
    }

    //update() update the post
    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:1048'
                                #multipurpose Internet Mail Extensions
        ]);

        $post           = $this->post->findOrFail($id);
        $post->description = $request->description;

        if($request->image){
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        # Delete all records from categoryPost related to this post
        $post->categoryPost()->delete();

        # Save the new categories to category_post pivot table
        foreach($request->category as $category_id){
            $category_post[] = [
                'category_id' => $category_id
            ];
        }
        $post->categoryPost()->createMany($category_post);

        # redirect to show post page
        return redirect()->route('post.show', $id);
    }

    // delete post
    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);

        // We have onDelete('cascade'), so below is unnessesary
        // $post->categoryPost()->delete();
        $post->delete();

        return redirect()->route('index');
    }

    // Search by category
    // Display all the posts with the tag, EXCEPT private and deactivated accounts' posts
    public function searchByTag($category_id)
    {
        $posts = $this->post
                      ->whereHas('categoryPost', function($query) use ($category_id){
                            $query->where('category_id', $category_id);
                      })
                      ->whereHas('user', function($query){
                            $query->whereNull('deleted_at');
                      })
                      ->with(['user', 'categoryPost'])
                      ->get()
                      // If private user's post, check if Auth user is allowed to display it
                      ->filter(function ($post) {
                            return Gate::allows('viewProfile', [$post->user, Auth::user()]);
                        })
                      ->values(); // ← filterのあとindexを振り直す

        return $posts;
    }

    public function showTagSearch($category_id)
    {
        $posts         = $this->searchByTag($category_id);
        $category_name = $this->category->findOrFail($category_id);

        return view('users.posts.tagsearch')
             ->with('posts', $posts)
             ->with('category_name', $category_name);
    }

}
