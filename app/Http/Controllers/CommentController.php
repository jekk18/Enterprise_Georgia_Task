<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{ 
    // დავამატეთ $locale პარამეტრი
    public function store(CommentRequest $request, $locale, Post $post)
    { 
        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'locale' => $locale, // ვინახავთ მიმდინარე ენას
        ]);

        return back()->with('success', __('messages.comment_added'));
    }
 
    public function update(CommentRequest $request, $locale, Comment $comment)
    {  
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $comment->update([
            'content' => $request->content,
            'is_edited' => true  
        ]);

        return back()->with('success', __('messages.comment_updated'));
    }
 
    public function destroy($locale, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', __('messages.comment_deleted'));
    }
}
