<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{ 
    public function showLogin() {
        return view('auth.login');
    }
 
   public function login(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
 
        if ($user->isAdmin()) {
            return redirect()->route('admin.users');
        } elseif ($user->isModerator()) {
            return redirect()->route('moderator.dashboard');
        }
 
        return redirect()->intended('/profile');
    }

    return back()->withErrors(['email' => 'მონაცემები არასწორია']);
} 
   
    public function profile() {
    $user = Auth::user();
 
    $user->load(['posts' => function ($query) {
        $query->latest(); 
    }]);
 
    return view('profile.profile', compact('user'));
}
 
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
