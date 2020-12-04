<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
     * Display all of the posts of a particular user
     * 
     * @param Request $request
     * @return view
     */
    public function posts(Request $request)
    {
        $user = $request->user();
        $posts = Post::where('author_id', $user->id)->orderBy('publication_date', 'desc')->paginate(5);
        $title = $user->name;
        return view('home')->withPosts($posts)->withTitle($title);
    }

    /**
     * profile for user
     */
    public function profile(Request $request, $id)
    {
        $data['user'] = User::find($id);

        if (!$data['user']) {
            return redirect('/');
        }

        if ($request->user() && $data['user']->id == $request->user()->id) {
            $data['author'] = true;
        } else {
            $data['author'] = null;
        }

        $data['posts_count'] = $data['user']->posts->count();
        $data['latest_posts'] = $data['user']->posts->take(5);
        
        return view('users.profile', $data);
    }
}
