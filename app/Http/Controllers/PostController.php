<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 
use App\Notifications\NewPostPending;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
public function index($locale)
{
    $posts = Post::where('status', 'approved')
        ->with(['user', 'translations' => function($q) use ($locale) {
            // ვიღებთ მხოლოდ მიმდინარე ენის თარგმანს (სათაური, აღწერა, სურათი)
            $q->where('locale', $locale);
        }])
        ->withCount(['comments' => function($query) use ($locale) {
            // ვითვლით მხოლოდ იმ კომენტარებს, რომლებიც ამ ენაზეა დაწერილი
            $query->where('locale', $locale);
        }])
        ->latest()
        ->paginate(15);

    return view('posts.index', compact('posts', 'locale'));
}

    public function show($locale, Post $post)
    {
        if ($post->status !== 'approved' && !Auth::check()) {
            abort(403, __('messages.access_denied'));
        }

        // ვტვირთავთ მხოლოდ მიმდინარე ენის თარგმანს და კომენტარებს
        $post->load(['user', 'translations' => function($q) use ($locale) {
            $q->where('locale', $locale);
        }, 'comments' => function($query) use ($locale) {
            $query->where('locale', $locale) // მხოლოდ ამ ენის კომენტარები
                  ->whereNull('parent_id')
                  ->with('replies.user', 'user');
        }]);

        return view('posts.show', compact('post'));
    }
    
    public function create($locale)
    {
        return view('posts.create');
    } 

    public function store(PostRequest $request, $locale)
    {
        return DB::transaction(function () use ($request, $locale) {
            
            $post = Post::create([
                'user_id' => Auth::id(),
                'category' => $request->category,
                'status' => 'pending',
            ]);

            $locales = ['ka', 'en'];

            foreach ($locales as $l) {
                $imagePath = null;
                $fieldName = "image_{$l}";

                if ($request->hasFile($fieldName)) {
                    $imagePath = $request->file($fieldName)->store("posts/{$l}", 'public');
                }

                $post->translations()->create([
                    'locale'      => $l,
                    'title'       => $request->input("title_{$l}"),
                    'description' => $request->input("description_{$l}"),
                    'image'       => $imagePath,
                ]);
            }

            // ნოტიფიკაცია მოდერატორებს
            $moderators = User::whereHas('role', function($q) {
                $q->where('name', 'moderator');
            })->get();

            foreach ($moderators as $moderator) {
                $moderator->notify(new NewPostPending($post));
            }

            return redirect()
                ->route('profile', ['locale' => $locale])
                ->with('success', __('messages.post_sent_to_moderation'));
        });
    }

    public function edit($locale, Post $post)
    {
        if (Auth::id() !== $post->user_id) abort(403);
        
        // ვტვირთავთ ყველა თარგმანს რედაქტირებისთვის
        $post->load('translations');
        
        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, $locale, Post $post)
    {
        if (Auth::id() !== $post->user_id) abort(403);

        return DB::transaction(function () use ($request, $post, $locale) {
            $post->update([
                'category' => $request->category,
                'status' => 'pending',
                'is_edited' => true
            ]);

            $locales = ['ka', 'en'];

            foreach ($locales as $l) {
                $translation = $post->translations()->where('locale', $l)->first();
                $data = [
                    'title' => $request->input("title_{$l}"),
                    'description' => $request->input("description_{$l}"),
                ];

                if ($request->hasFile("image_{$l}")) {
                    if ($translation && $translation->image) {
                        Storage::disk('public')->delete($translation->image);
                    }
                    $data['image'] = $request->file("image_{$l}")->store("posts/{$l}", 'public');
                }

                $post->translations()->updateOrCreate(
                    ['locale' => $l],
                    $data
                );
            }

            return redirect()->route('profile', ['locale' => $locale])
                             ->with('success', __('messages.post_updated_moderation'));
        });
    }

    public function destroy($locale, Post $post)
    {
        if (Auth::id() !== $post->user_id) abort(403);

        // თარგმანების სურათების წაშლა
        foreach ($post->translations as $translation) {
            if ($translation->image) {
                Storage::disk('public')->delete($translation->image);
            }
        }

        $post->delete();

        return redirect()->route('profile', ['locale' => $locale])
                         ->with('success', __('messages.post_deleted_success'));
    }
}