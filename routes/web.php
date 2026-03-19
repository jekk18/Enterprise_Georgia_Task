<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

// 1. ღია როუტები (ყველასთვის)
Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/post/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. საერთო ავტორიზებული როუტები (ნებისმიერი შესული იუზერისთვის)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // კომენტარები
    Route::post('/post/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comment/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/notifications/{id}/read', function ($id) {
        Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    })->name('notifications.read');
});

// 3. ადმინის ზონა (მხოლოდ Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
});

// 4. მოდერატორის ზონა (მხოლოდ Moderator)
Route::middleware(['auth', 'role:moderator'])->group(function () {
   Route::get('/moderator/dashboard', [ModeratorController::class, 'index'])->name('moderator.dashboard');
    
    // პოსტის სტატუსის განახლება
    Route::post('/moderator/post/{post}/status', [ModeratorController::class, 'updateStatus'])->name('moderator.updateStatus');
});
 