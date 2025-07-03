<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected $user = null;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $this->checkIpSecurity();

        $credentials = [
            'email' => strtolower($this->input('email')),
            'password' => $this->input('password'),
        ];

        // Find the user first
        $this->user = User::where('email', $credentials['email'])->first();

        if (!$this->user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address. Please check your email or register for a new account.',
            ]);
        }

        // Check if account is locked
        if ($this->user->login_attempts >= 5) {
            $lockoutTime = now()->addMinutes(15);
            Cache::put("user_lockout_{$this->user->id}", true, $lockoutTime);
            
            throw ValidationException::withMessages([
                'email' => "Your account has been locked due to too many failed attempts. Please try again after {$lockoutTime->diffForHumans()}.",
            ]);
        }

        // Check password specifically
        if (!\Hash::check($credentials['password'], $this->user->password)) {
            // Increment failed attempts
            $this->user->incrementLoginAttempts();

            RateLimiter::hit($this->throttleKey());
            
            $remainingAttempts = 5 - $this->user->login_attempts;
            $message = 'The password you entered is incorrect.';
            
            if ($remainingAttempts > 0) {
                $message .= " You have {$remainingAttempts} attempt(s) remaining before your account is locked.";
            }
            
            throw ValidationException::withMessages([
                'password' => $message,
            ]);
        }

        // If we get here, both email and password are correct, so attempt login
        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            // This should rarely happen, but just in case
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Login failed due to a system error. Please try again.',
            ]);
        }

        // Reset rate limiter and login attempts on successful login
        RateLimiter::clear($this->throttleKey());
        $this->user->resetLoginAttempts();

        // Record successful login
        $this->user->recordLogin($this->ip());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function checkIpSecurity(): void
    {
        $ip = $this->ip();
        $key = "login_attempts_ip_{$ip}";
        $attempts = Cache::get($key, 0);

        // If more than 20 attempts from same IP in 1 hour
        if ($attempts >= 20) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts from this IP. Please try again later.',
            ]);
        }

        Cache::put($key, $attempts + 1, now()->addHour());
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}