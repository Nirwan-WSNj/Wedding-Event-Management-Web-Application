<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', 'min:8'],
        'terms' => ['accepted'],
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => strtolower($request->email),
        'password' => Hash::make($request->password),
        'role' => 'customer', // if you're not using roles, you can remove this line
    ]);

    event(new Registered($user));

    // 🔐 Log in the user right after registration
    Auth::login($user);

    // ✅ Redirect to home page with user logged in (so profile icon shows)
    return redirect()->intended(route('home'));

}

}