<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED)
                : redirect()->guest(route('login'));
        }

        $user = Auth::user();
        
        if (!in_array($user->role, $roles)) {
            $rolesList = implode(', ', array_map(function($role) {
                return ucfirst($role);
            }, $roles));
            
            $message = "This action requires one of the following roles: {$rolesList}.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized action.',
                    'details' => $message
                ], Response::HTTP_FORBIDDEN);
            }

            return abort(Response::HTTP_FORBIDDEN, $message);
        }

        // Add role to request for use in controllers
        $request->merge(['current_role' => $user->role]);

        return $next($request);
    }
}