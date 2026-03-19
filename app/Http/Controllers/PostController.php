<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewPostPending;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'approved')
            ->with('user')
            ->withCount('comments')
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if ($post->status !== 'approved' && !Auth::check()) {
            abort(403, 'თქვენ არ გაქვთ ამ პოსტის ნახვის უფლება.');
        }

        $post->load(['user', 'comments' => function($query) {
            $query->whereNull('parent_id')->with('replies.user', 'user');
        }]);

        return view('posts.show', compact('post'));
    }
    
    public function create()
    {
        return view('posts.create');
    } 
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) { 
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'image' => $imagePath,
            'status' => 'pending',  
        ]);

        $moderators = User::where('role_id', 2)->get();
        foreach ($moderators as $moderator) {
            $moderator->notify(new NewPostPending($post));
        }

        return redirect()->route('profile')->with('success', 'სტატია წარმატებით გაიგზავნა მოდერაციაზე!');
    } 

    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) abort(403);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'category']);

        // სურათის განახლების ლოგიკა
        if ($request->hasFile('image')) { 
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $data['is_edited'] = true;
        $data['status'] = 'pending'; 

        $post->update($data);

        return redirect()->route('profile')->with('success', 'სტატია განახლდა და გაიგზავნა ხელახალ მოდერაციაზე!');
    }

    // პოსტის წაშლა (ახალი მეთოდი)
    public function destroy(Post $post)
    {
        // მხოლოდ ავტორს შეუძლია თავისი პოსტის წაშლა
        if (Auth::id() !== $post->user_id) abort(403);

        // სურათის წაშლა სერვერიდან
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('profile')->with('success', 'სტატია წარმატებით წაიშალა!');
    }
}