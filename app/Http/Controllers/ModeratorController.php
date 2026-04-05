<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostStatusNotification; 
use App\Http\Requests\UpdatePostStatusRequest;

class ModeratorController extends Controller
{
    public function index($locale) 
    { 
        // მოგვაქვს მხოლოდ ის პოსტები, რომლებიც ელოდება განხილვას
        $pendingPosts = Post::where('status', 'pending')->with('user')->latest()->get();
        return view('moderator.dashboard', compact('pendingPosts'));
    }

    public function updateStatus(UpdatePostStatusRequest $request, $locale, Post $post) 
    {  
        // ვამოწმებთ, ხომ არ დაასწრო სხვა მოდერატორმა
        if ($post->status !== 'pending') {
            return redirect()->back()->with('error', __('messages.post_already_processed'));
        }  

        $post->update(['status' => $request->status]);
 
        $isApproved = $request->status === 'approved';
        
        // ტექსტი ნოტიფიკაციისთვის (ბაზაში შესანახად ან საჩვენებლად)
        $statusText = $isApproved ? __('messages.status_approved') : __('messages.status_rejected');
        
        // შეტყობინება მოდერატორისთვის
        $message = $isApproved ? __('messages.post_approved_success') : __('messages.post_rejected_success');
 
        // ვუგზავნით ნოტიფიკაციას ავტორს
        $post->user->notify(new PostStatusNotification($post, $statusText));

        return back()->with('success', $message);
    }
}