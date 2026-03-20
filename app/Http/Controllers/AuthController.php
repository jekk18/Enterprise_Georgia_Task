<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

 public function showRegister() {
        return view('auth.register');
    }

public function register(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:2|confirmed', // confirmed ითხოვს password_confirmation ველს
    ]);

    $userRole = Role::where('name', 'user')->first();
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $userRole->id, // ყველა ახალი დარეგისტრირებული არის "User"
    ]);

    Auth::login($user);

    return redirect()->route('profile')->with('success', 'რეგისტრაცია წარმატებით გაიარეთ!');
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
