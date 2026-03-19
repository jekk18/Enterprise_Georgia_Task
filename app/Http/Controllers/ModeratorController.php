<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostStatusNotification; // <-- ეს აუცილებლად დაამატე თავში

class ModeratorController extends Controller
{
    public function index() { 
        $pendingPosts = \App\Models\Post::where('status', 'pending')->with('user')->latest()->get();
        return view('moderator.dashboard', compact('pendingPosts'));
    }

    public function updateStatus(Request $request, Post $post) {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $post->update(['status' => $request->status]);

        // ქართული ტექსტის მომზადება ნოთიფიკაციისთვის
        $statusText = $request->status === 'approved' ? 'დადასტურდა' : 'უარყოფილია';

        // ნოთიფიკაციის გაგზავნა პოსტის ავტორისთვის
        $post->user->notify(new PostStatusNotification($post, $statusText));

        $message = $request->status === 'approved' ? 'პოსტი დადასტურდა!' : 'პოსტი უარყოფილია!';
        
        return back()->with('success', $message);
    }
}
