<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGoogleAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the Google access token exists in the session
        if (!session()->has('google_access_token')) {
            // Redirect to the Google OAuth flow if not authenticated
            return redirect()->route('google.redirect');
        }

        return $next($request);
    }
}
