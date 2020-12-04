<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostFormRequest;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('publication_date', 'desc')->paginate(5);

        return view('home')->withPosts($posts)->withTitle('Latest Posts');
    }

    public function create(Request $request)
    {
        if ($request->user()->can_post()) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }

    public function store(PostFormRequest $request)
    {
        $post = new Post();
        $post->title = $request->get('title');
        $post->description = $request->get('description');
        $post->slug = Str::slug($post->title);

        $duplicate = Post::where('slug', $post->slug)->first();

        if ($duplicate) {
            return redirect('add-post')->withErrors('Title already exists.')->withInput();
        }

        $post->author_id = $request->user()->id;
        $post->publication_date = Carbon::now();
        $message = 'Post published successfully';

        $post->save();
        return redirect('/' . $post->slug)->withMessage($message);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        
        if (!$post) {
            return redirect('/')->withErrors('requested page not found');
        }
        
        return view('posts.show')->withPost($post);
    }

    public function edit(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ($post && ($request->user()->is_admin())) {
            return view('posts.edit')->with('post', $post);
        }

        return redirect('/')->withErrors('you have not sufficient permissions');
    }

    public function update(Request $request)
    {
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);

        if ($post && ($request->user()->is_admin())) {
            $title = $request->input('title');
            $slug = Str::slug($title);
            $duplicate = Post::where('slug', $slug)->first();

            if ($duplicate) {
                if ($duplicate->id != $post_id) {
                    return redirect('edit/' . $post->slug)->withErrors('Title already exists.')->withInput();
                } else {
                    $post->slug = $slug;
                }
            }

            $post->title = $title;
            $post->description = $request->input('description');

            $message = 'Post updated successfully';
            $landing = $post->slug;

            $post->save();
            return redirect($landing)->withMessage($message);
        } else {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);

        if ($post && ($request->user()->is_admin())) {
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        } else {
            $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }

        return redirect('/')->with($data);
    }
}
