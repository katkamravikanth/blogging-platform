<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostFormRequest;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /*
     * Display all of the posts sort by publication date
     * 
     * @return view
     */
    public function index()
    {
        $posts = Post::orderBy('publication_date', 'desc')->paginate(5);

        return view('home')->withPosts($posts)->withTitle('Latest Posts');
    }

    /*
     * Post create form
     * 
     * @param Request $request
     * @return view
     */
    public function create(Request $request)
    {
        if ($request->user()->can_post()) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }

    /*
     * Post store
     * 
     * @param Request $request
     * @redirect to post show
     */
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

    /*
     * Post show
     * 
     * @param $slug
     * @return view
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        
        if (!$post) {
            return redirect('/')->withErrors('requested page not found');
        }
        
        return view('posts.show')->withPost($post);
    }

    /*
     * Post edit form
     * 
     * @param Request $request, $slug
     * @return view
     */
    public function edit(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ($post && ($request->user()->is_admin())) {
            return view('posts.edit')->with('post', $post);
        }

        return redirect('/')->withErrors('you have not sufficient permissions');
    }

    /*
     * Post update
     * 
     * @param Request $request
     * @redirect to post show
     */
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

    /*
     * Post destroy
     * 
     * @param Request $request, $id
     * @redirect to home
     */
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

    /*
     * Import Posts from API
     * 
     * @return import status
     */
    public function importPosts()
    {
        $endpoint = env('API_PATH');
        $response = Http::get($endpoint);

        if ($response->status() === 200) {
            $resultData = $response->json()['data'];

            $added = 0;
            $duplicates = 0;

            foreach ($resultData as $key => $data) {
                $post = new Post();
                $post->title = $data['title'];
                $post->description = $data['description'];
                $post->slug = Str::slug($post->title);
                $duplicate = Post::where('slug', $post->slug)->first();

                if ($duplicate) {
                    $duplicates++;
                } else {
                    $added++;
                }

                $post->author_id = 0;
                $post->publication_date = $data['publication_date'];

                $post->save();
            }

            return redirect('/')->with('status', true)->with('added', $added)->with('duplicates', $duplicates);
        }
        return redirect('/')->with('status', false)->with('message', 'Unable to access the API');
    }
}
