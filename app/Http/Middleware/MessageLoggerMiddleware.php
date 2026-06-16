<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\MessageLogger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ne loguer que les routes de messagerie
        if (strpos($request->path(), 'messagerie') === 0 || $request->path() === 'messagerie') {
            MessageLogger::log(
                'request_start',
                null,
                null,
                [
                    'event' => 'request_started',
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'path' => $request->path()
                ],
                MessageLogger::LEVEL_DEBUG
            );
        }

        $response = $next($request);

        // Log après la réponse
        if (strpos($request->path(), 'messagerie') === 0 || $request->path() === 'messagerie') {
            MessageLogger::log(
                'request_end',
                null,
                null,
                [
                    'event' => 'request_completed',
                    'status_code' => $response->getStatusCode(),
                    'duration_ms' => (microtime(true) - LARAVEL_START) * 1000
                ],
                MessageLogger::LEVEL_DEBUG
            );
        }

        return $response;
    }
}