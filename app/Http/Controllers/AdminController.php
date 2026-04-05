<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReReviewPostNotification;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Requests\SearchPostsRequest;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\GenericExport;
use App\Services\ExportService;

class AdminController extends Controller
{ 

    public function __construct(
            protected ExportService $exportService
        ) {}

    // დავამატეთ $locale
    public function index($locale) {
        $users = User::with('role')->get(); 
        $roles = Role::all(); 
        return view('admin.users', compact('users', 'roles'));
    }   
 
    public function updateRole(UpdateUserRoleRequest $request, $locale, User $user) 
    {  
        if (Auth::id() === $user->id) {  
            // აქ 'admin' სახელით შემოწმება აჯობებს ID-ს, უფრო უსაფრთხოა
            $adminCount = User::whereHas('role', function($q) {
                $q->where('name', 'admin');
            })->count();

            if ($adminCount <= 1 && $request->role_id != $user->role_id) {
                return back()->with('error', __('messages.last_admin_error'));
            }

            $user->update(['role_id' => $request->role_id]);
            // რედირექტი პროფილზე ენის გათვალისწინებით
            return redirect()->route('profile', ['locale' => $locale])
                           ->with('success', __('messages.role_updated_self'));
        }

        $user->update(['role_id' => $request->role_id]);
        return back()->with('success', __('messages.role_updated_success'));
    }

  public function allPosts(SearchPostsRequest $request, $locale) 
{
    // ვიყენებთ Eager Loading-ს, რომ Blade-ში ერორები არ გვქონდეს
    $query = Post::with(['user', 'translations']); 

    if ($request->filled('search')) {
        $searchTerm = $request->search;
         
        $query->where(function($q) use ($searchTerm) {
            // 1. ვეძებთ თარგმანების ცხრილში (სათაურში)
            $q->whereHas('translations', function($transQuery) use ($searchTerm) {
                $transQuery->where('title', 'like', '%' . $searchTerm . '%');
            })
            // 2. ან ვეძებთ ავტორის სახელით
            ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                $userQuery->where('name', 'like', '%' . $searchTerm . '%');
            });
        });
    }

    $posts = $query->latest()->paginate(10)->withQueryString();

    return view('admin.posts', compact('posts'));
}

    // --- აქ იწყება ექსპორტის ლოგიკა ---

        /**
         * მომხმარებლების ექსპორტი
         */
        // public function exportUsers($locale) 
        // {
        //     $users = User::with('role')->get();
            
        //     $headings = [
        //         __('messages.id'), 
        //         __('messages.name'), 
        //         __('messages.email'), 
        //         __('messages.role'), 
        //         __('messages.date')
        //     ];

        //     return Excel::download(new GenericExport($users, $headings, function($user) {
        //         return [
        //             $user->id,
        //             $user->name,
        //             $user->email,
        //             $user->role->name ?? 'N/A',
        //             $user->created_at->format('d/m/Y')
        //         ];
        //     }), 'users_list.xlsx');
        // }

        /**
         * პოსტების ექსპორტი (თარგმანების გათვალისწინებით)
         */
        // public function exportPosts($locale) 
        // {
        //     // მოგვაქვს პოსტები მხოლოდ მიმდინარე ენის თარგმანით
        //     $posts = Post::with(['user', 'translations' => function($q) use ($locale) {
        //         $q->where('locale', $locale);
        //     }])->get();

        //     $headings = [
        //         __('messages.post_title'), 
        //         __('messages.author'), 
        //         __('messages.status'), 
        //         __('messages.date')
        //     ];

        //     return Excel::download(new GenericExport($posts, $headings, function($post) {
        //         return [
        //             $post->translations->first()->title ?? 'No Title',
        //             $post->user->name,
        //             $post->status,
        //             $post->created_at->format('d/m/Y')
        //         ];
        //     }), 'posts_report.xlsx');
        // }

    public function exportUsers()
    {
        return $this->exportService->exportToExcel(
            type: 'users',
            fileName: 'users_list.xlsx',
            headings: ['ID', 'სახელი', 'Email', 'როლი', 'თარიღი'],
            mapping: fn($user) => [
                $user->id,
                $user->name,
                $user->email,
                $user->role->name ?? 'User',
                $user->created_at->format('d/m/Y'),
            ]
        );
    }
    public function exportPosts(string $locale)
    {
        return $this->exportService->exportToExcel(
            type: 'posts',
            fileName: "posts_report_{$locale}.xlsx",
            headings: ['ID', 'სათაური', 'ავტორი', 'სტატუსი'],
            mapping: fn($post) => [
                $post->id,
                $post->translations->first()?->title ?? 'N/A',
                $post->user->name,
                $post->status,
            ],
            filters: ['locale' => $locale]
        );
    }


 

    // --- ექსპორტის დასასრული ---


    public function reReview($locale, Post $post)
    {
        // isAdmin() მეთოდი მოდელში უნდა გქონდეს
        if (!auth()->user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $post->update(['status' => 'pending']);

        $moderators = User::whereHas('role', function($query) {
            $query->where('name', 'moderator');
        })->get();

        if ($moderators->count() > 0) {
            foreach ($moderators as $moderator) {
                $moderator->notify(new ReReviewPostNotification($post, auth()->user()->name));
            }
        }

        return back()->with('success', __('messages.post_sent_to_review'));
    }

    public function destroyPost($locale, Post $post)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $post->delete();

        return back()->with('success', __('messages.post_deleted_success'));
    } 
}
