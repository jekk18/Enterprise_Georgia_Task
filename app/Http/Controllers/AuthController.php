<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{ 
    public function showLogin($locale) {
        return view('auth.login');
    }
 
    public function login(LoginRequest $request, $locale) { 

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // რედირექტი როლების მიხედვით ენის გათვალისწინებით
            if ($user->isAdmin()) {
                return redirect()->route('admin.users', ['locale' => $locale]);
            } elseif ($user->isModerator()) {
                return redirect()->route('moderator.dashboard', ['locale' => $locale]);
            }

            // intended() ფუნქციასაც უნდა ჰქონდეს ლოკალი
            return redirect()->intended($locale . '/profile');
        }

        return back()->withErrors(['email' => __('messages.login_error')]);
    }

    public function showRegister($locale) {
        return view('auth.register');
    }

    public function register(RegisterRequest $request, $locale) { 
            
        $userRole = Role::where('name', 'user')->first();
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id, 
        ]);

        Auth::login($user);

        return redirect()->route('profile', ['locale' => $locale])
                         ->with('success', __('messages.registration_success'));
    }
   
    public function profile($locale) {
        $user = Auth::user();
    
        $user->load(['posts' => function ($query) {
            $query->latest(); 
        }]);
    
        return view('profile.profile', compact('user'));
    }
 
    public function logout(Request $request, $locale) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // ლოგაუტის შემდეგ ისევ ლოგინის გვერდზე ენის შენარჩუნებით
        return redirect()->route('login', ['locale' => $locale]);
    }
}
