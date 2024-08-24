<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        try {
            // Pass request to the next middleware/handler
            $response = $next($request);

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            // Log request and response details after the response is generated
            Log::channel('logger')->info('Request and Response', [
                'timestamp' => now(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_headers' => $request->headers->all(),
                'response_status' => $response->getStatusCode(),
                'response_content' => $response->getContent(),
                'execution_time' => $executionTime,
            ]);

            return $response;
        } catch (Throwable $e) {
            // Log error details to the default 'laravel.log' file
            Log::error('Error', [
                'timestamp' => now(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to ensure normal error handling occurs
            throw $e;
        }
    }
}
