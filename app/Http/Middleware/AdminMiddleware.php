<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == User::ADMIN_ROLE_ID) {
            return $next($request);
        }

        return redirect()->route('index'); // go to homepage
    }
    // Request $request -> This parameter represents the incoming HTTP request.
    // Closure $next -> A closure that represents the next step in the middleware.
    // Auth::check() -> This checks if the current user is authenticated.
    // return $next($request) -> If the user is the authenticated and has an admin role, the middleware allows the request to proceed.
}
