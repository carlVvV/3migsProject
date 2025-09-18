<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Remove problematic middleware - let routes handle authentication
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        try {
            // Log the user out
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();
            
            // Regenerate the CSRF token
            $request->session()->regenerateToken();

            // Clear any cached data
            if (function_exists('cache')) {
                cache()->flush();
            }

            // Clear any remember me tokens
            if ($request->hasCookie('remember_web')) {
                $cookie = cookie()->forget('remember_web');
                return redirect()->route('login')
                    ->with('status', 'You have been successfully logged out.')
                    ->withCookie($cookie);
            }

            // Clear any other authentication cookies
            $request->session()->forget('auth');
            $request->session()->forget('user_id');

            // Force redirect to login page with success message
            return redirect()->route('login')
                ->with('status', 'You have been successfully logged out.')
                ->withHeaders([
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]);
            
        } catch (\Exception $e) {
            // If there's an error, still try to redirect to login
            return redirect()->route('login')->with('error', 'An error occurred during logout.');
        }
    }
}
