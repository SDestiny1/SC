<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Comment;
use MongoDB\BSON\ObjectId;
use App\Models\User; // Assuming you have a User model to fetch users
class PostController extends Controller
{
public function index()
{
    $posts = Post::orderBy('created_at', 'desc')->get();
    $comments = Comment::all();
    $users = User::all(); // Assuming you have a User model to fetch users

    foreach ($posts as $post) {
        $post->comments_count = Comment::where('post_id', new ObjectId((string) $post->_id))->count();
    }
    return view('posts.index', compact('posts', 'comments', 'users'));
}

    // Delete a post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}

