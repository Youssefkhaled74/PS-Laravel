<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceAcceptJson
{
    /**
     * Force API requests to accept JSON so Laravel returns JSON errors instead of redirects.
     */
    public function handle(Request $request, Closure $next)
    {
        // If the request is under /api or starts with v1, force Accept header
        $path = $request->path();
        if (str_starts_with($path, 'api/') || str_starts_with($path, 'v1/') || $request->is('api/*') || $request->is('v1/*')) {
            $request->headers->set('Accept', 'application/json');
            // mark as AJAX too
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        }

        return $next($request);
    }
}
