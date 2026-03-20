<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReReviewPostNotification;

class AdminController extends Controller
{ 
    public function index() {
        $users = User::with('role')->get(); 
        $roles = Role::all(); 
        return view('admin.users', compact('users', 'roles'));
    }   
 
    public function updateRole(Request $request, User $user) 
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        // თუ ადმინი ცდილობს საკუთარი როლის შეცვლას
        if (Auth::id() === $user->id) {
            // თუ ბაზაში მხოლოდ 1 ადმინია, არ მივცეთ უფლება როლი შეიცვალოს
            $adminCount = User::where('role_id', 3)->count(); // დავუშვათ 3 არის Admin ID
            if ($adminCount <= 1 && $request->role_id != 3) {
                return back()->with('error', 'თქვენ ხართ ერთადერთი ადმინი და როლის შეცვლა შეუძლებელია!');
            }

            $user->update(['role_id' => $request->role_id]);
            
            // თუ საკუთარი როლი შეიმცირა, გადავიყვანოთ პროფილზე
            return redirect()->route('profile')->with('success', 'თქვენი როლი შეიცვალა.');
        }

        // სხვა იუზერის როლის შეცვლა
        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', 'მომხმარებლის როლი წარმატებით შეიცვალა!');
    }

    public function allPosts(Request $request)
    {
        $query = Post::query(); 
        // ძებნის ლოგიკა (სათაურის ან ავტორის მიხედვით)
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        // წამოვიღოთ პოსტები (Pending, Approved, Rejected - ყველა ერთად)
        $posts = $query->latest()->paginate(10)->withQueryString();

        return view('admin.posts', compact('posts'));
    }

   public function reReview(Post $post)
{
    if (!auth()->user()->isAdmin()) {
        abort(403, 'წვდომა აკრძალულია.');
    }

    $post->update(['status' => 'pending']);

    // ვეძებთ მომხმარებლებს, რომლებსაც აქვთ როლი სახელით 'moderator'
    // 'role' აქ არის შენი User მოდელის ფუნქციის სახელი
    $moderators = User::whereHas('role', function($query) {
        $query->where('name', 'moderator');
    })->get();

    if ($moderators->count() > 0) {
        foreach ($moderators as $moderator) {
            $moderator->notify(new ReReviewPostNotification($post, auth()->user()->name));
        }
    }

    return back()->with('success', 'პოსტი გადაეგზავნა მოდერატორებს განსახილველად.');
}
public function destroyPost(Post $post)
{
    // მხოლოდ ადმინს შეუძლია წაშლა
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $post->delete();

    return back()->with('success', 'პოსტი წარმატებით წაიშალა.');
}


}
