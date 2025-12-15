<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // If it's an AJAX request asking for a new token
        if ($request->ajax() && $request->is('refresh-csrf')) {
            // Regenerate token
            Session::regenerateToken();

            return response()->json([
                'csrf_token' => csrf_token(),
            ]);
        }

        return $next($request);
    }
}
