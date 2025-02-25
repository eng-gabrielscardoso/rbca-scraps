<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Posts', [
            'posts' => Post::with('user:id,name,email', 'comments.user:id,name,email')->get(),
        ]);
    }

    public function deleteComment(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorised'], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment delete successfully!');
    }

    public function updateComment(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorised'], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment->update($validated);

        return redirect()->back()->with('success', 'Comment updated!');
    }

    public function deletePost(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorised'], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }

        $post->delete();

        return redirect()->route('posts')->with('success', 'Post deleted successfully!');
    }

    public function updatePost(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorised'], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->back()->with('success', 'Post updated!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Auth::user()->posts()->create($validated);

        return redirect()->route('posts')->with('success', 'Post created successfully!');
    }

    public function storeComment(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($validated['post_id']);

        $comment = new Comment;
        $comment->content = $validated['content'];
        $comment->user()->associate(Auth::user());
        $comment->post()->associate($post);
        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
}
