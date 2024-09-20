<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (auth()->user() && in_array(auth()->user()->role->role_type, $role)) {
            return $next($request);
        } else {
            return response()->json(["status" => 403, "message" => "Access denied"], Response::HTTP_FORBIDDEN);
        }
    }
}
