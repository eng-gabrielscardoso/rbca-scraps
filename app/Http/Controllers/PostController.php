<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
}
