<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            $allowedRoutes = ['dashboard', 'logout'];
            $currentRouteName = $request->route() ? $request->route()->getName() : null;

            if (!in_array($currentRouteName, $allowedRoutes)) {
                return redirect()->route('dashboard')->with('error', 'Akun Anda telah dinonaktifkan. Anda tidak dapat mengakses fitur ini.');
            }
        }

        return $next($request);
    }
}
