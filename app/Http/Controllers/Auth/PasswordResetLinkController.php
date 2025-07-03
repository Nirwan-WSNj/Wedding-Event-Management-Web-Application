<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Log the password reset attempt
            \Log::info('Password reset requested for email: ' . $request->email);

            // Check if user exists first
            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                \Log::warning('Password reset requested for non-existent email: ' . $request->email);
                // Still return success message for security (don't reveal if email exists)
                return back()->with('status', 'We have emailed your password reset link!');
            }

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            \Log::info('Password reset status: ' . $status . ' for email: ' . $request->email);

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', __($status))
                        : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage() . ' for email: ' . $request->email);
            return back()->withErrors(['email' => 'An error occurred while sending the reset link. Please try again.']);
        }
    }
}
