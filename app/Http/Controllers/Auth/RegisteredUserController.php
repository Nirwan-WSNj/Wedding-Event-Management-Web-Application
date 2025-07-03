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
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'terms' => ['accepted'],
        ], [
            // First Name validation messages
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot be longer than 255 characters.',
            'first_name.regex' => 'First name can only contain letters and spaces.',
            
            // Last Name validation messages
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name cannot be longer than 255 characters.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            
            // Email validation messages
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot be longer than 255 characters.',
            'email.unique' => 'An account with this email address already exists. Please use a different email or try logging in.',
            
            // Phone validation messages
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.regex' => 'Please enter a valid phone number (numbers, spaces, dashes, and parentheses only).',
            
            // Password validation messages
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            
            // Terms validation message
            'terms.accepted' => 'You must accept the Terms of Service and Privacy Policy to register.',
        ]);

        try {
            $user = User::create([
                'first_name' => ucwords(strtolower($request->input('first_name'))),
                'last_name' => ucwords(strtolower($request->input('last_name'))),
                'email' => strtolower($request->input('email')),
                'phone' => preg_replace('/[^0-9]/', '', $request->input('phone')),
                'password' => Hash::make($request->input('password')),
                'role' => 'customer',
                'status' => 'active',
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect()->intended(route('home'))->with('success', 'Registration successful! Welcome to our community.');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            // Show the real error for debugging
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration error: ' . $e->getMessage()]);
        }
    }

}