<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LogUserActivity
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
        // Check if there is an authenticated user
        if (auth()->check()) {
            // Log user activity
            Log::info('User Activity', [
                'user_id' => auth()->user()->id,
                'route' => $request->route()->getName(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                // Add more information as needed
            ]);
        }
    
        return $next($request);
    }
}
