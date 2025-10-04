<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        $user = auth()->user();
        
        // Check if user has the required role using Spatie Permission
        if ($role === 'admin') {
            if ($user->hasRole('admin')) {
                return $next($request);
            }
            
            // If user doesn't have admin role, redirect with error
            return redirect('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        // Check for other roles if needed
        if ($role === 'customer') {
            if ($user->hasRole('customer')) {
                return $next($request);
            }
            
            return redirect('/dashboard')->with('error', 'Access denied. Customer privileges required.');
        }

        // If role doesn't match, redirect to dashboard
        return redirect('/dashboard')->with('error', 'Access denied. Insufficient privileges.');
    }
}
