<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only count full-page HTML loads (Inertia visits use X-Inertia header)
        if (
            $request->isMethod('GET') &&
            !$request->is('admin/*') &&
            !$request->is('api/*') &&
            !$request->expectsJson() &&
            !$request->hasHeader('X-Inertia') &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html')
        ) {
            try {
                DB::table('page_views')->upsert(
                    [['date' => now()->toDateString(), 'count' => 1]],
                    ['date'],
                    ['count' => DB::raw('page_views.count + 1')]
                );
            } catch (\Throwable) {
                // Silently ignore if table doesn't exist yet
            }
        }

        return $response;
    }
}
