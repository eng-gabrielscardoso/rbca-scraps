<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/posts', [PostController::class, 'index'])->name('posts');

Route::middleware(['auth'])->group(function () {
    Route::delete('/comments/{comment}', [PostController::class, 'deleteComment'])->name('comments.delete');
    Route::put('/comments/{comment}', [PostController::class, 'updateComment'])->name('comments.update');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'deletePost'])->name('posts.delete');
    Route::put('/posts/{post}', [PostController::class, 'updatePost'])->name('posts.update');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
