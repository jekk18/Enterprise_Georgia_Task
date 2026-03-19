<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // 1. კომენტარის ან პასუხის დამატება
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id' // თუ პასუხია, უნდა არსებობდეს მშობელი
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'კომენტარი წარმატებით დაემატა!');
    }

    // 2. კომენტარის რედაქტირება (პუნქტი 5: Edited სტატუსი)
    public function update(Request $request, Comment $comment)
    {
        // უსაფრთხოების შემოწმება: მხოლოდ ავტორს შეუძლია
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $request->validate(['content' => 'required|string|max:1000']);

        $comment->update([
            'content' => $request->content,
            'is_edited' => true // ვნიშნავთ, რომ რედაქტირებულია
        ]);

        return back()->with('success', 'კომენტარი განახლდა!');
    }

    // 3. კომენტარის წაშლა
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'კომენტარი წაიშალა!');
    }
}
