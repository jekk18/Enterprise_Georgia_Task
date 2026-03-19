<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{ 
    public function index() {
        $users = User::with('role')->get(); 
        $roles = Role::all(); 
        return view('admin.users', compact('users', 'roles'));
    }

     
public function updateRole(Request $request, User $user) {
    $request->validate([
        'role_id' => 'required|exists:roles,id'
    ]);

    // საკუთარი ID-ს შემოწმება Auth ფასადით
    if (Auth::id() === $user->id) { 
        $user->update(['role_id' => $request->role_id]);
        
        return redirect()->route('profile')->with('success', 'თქვენი როლი შეიცვალა. წვდომა ადმინ-პანელზე შეზღუდულია.');
    }

    $user->update(['role_id' => $request->role_id]);

    return back()->with('success', 'მომხმარებლის როლი წარმატებით შეიცვალა!');
}
}
