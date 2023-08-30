<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class LogRequestsAndResponses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $logId = Uuid::uuid4()->toString();
        // Log the incoming request for API routes
        Log::info('API Request', [
            'log_id' => $logId,
            'method' => $request->method(),
            'path' => $request->path(),
            'query_parameters' => $request->query(),
            'request_body' => $request->all(),
        ]);

        // Proceed with the request and get the response
        $response = $next($request);

        // Log the outgoing response for API routes
        Log::info('API Response', [
            'log_id' => $logId,
            'status_code' => $response->getStatusCode(),
            'response_body' => json_decode($response->getContent(), true),
        ]);

        return $response;
    }
}
