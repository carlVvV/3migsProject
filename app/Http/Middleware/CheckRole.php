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
            return redirect('/login');
        }

        // For now, let's assume any authenticated user can access admin
        // You can customize this logic based on your user roles
        $user = auth()->user();
        
        // Simple role check - you can modify this based on your database structure
        if ($role === 'admin') {
            // Check if user has admin role (modify this based on your user table structure)
            // For example, if you have a 'role' column in users table:
            // if ($user->role === 'admin') {
            //     return $next($request);
            // }
            
            // For now, allow any authenticated user (temporary solution)
            return $next($request);
        }

        // If role doesn't match, redirect to dashboard
        return redirect('/dashboard')->with('error', 'Access denied. Admin privileges required.');
    }
}
