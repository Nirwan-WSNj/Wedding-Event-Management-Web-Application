<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        // Clear any old input data for fresh login page
        session()->forget('_old_input');
        
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();

            // Update user's last login information
            $user->update([
                'last_login_at' => Carbon::now(),
                'last_login_ip' => $request->ip(),
                'login_attempts' => 0 // Reset failed attempts
            ]);

            // Log successful login
            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            // Redirect based on user role
            $redirect = $this->getRedirectUrl($user);
            return redirect()->intended($redirect)
                ->with('success', 'Welcome back, ' . $user->first_name . '!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show the actual validation error message(s) to the user
            // Only preserve email input for validation errors, not password
            return back()->withErrors($e->errors())->withInput($request->only('email'));
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'error' => $e->getMessage() ?: 'An error occurred during login. Please try again.',
            ]); // Don't preserve input for system errors
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Log the logout
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been successfully logged out.');
    }

    protected function getRedirectUrl($user)
    {
        switch ($user->role) {
            case 'admin':
                return route('admin.dashboard');
            case 'manager':
                return route('manager.dashboard');
            default:
                return route('home');
        }
    }
}