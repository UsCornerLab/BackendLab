<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorHandlerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            // Log the error
            Log::error($e);

            // Format the error response
            $response = [
                'message' => 'An error occurred.',
                'code' => 500,
                'details' => env('APP_DEBUG') ? $e->getMessage() : null,
            ];

            // Return a JSON response with the error details
            return response()->json($response, $response['code']);
        }
    }
}