<?php

namespace App\Http\Middleware;

use Closure;

class ForceCors
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $origin = $request->headers->get('Origin');

        if ($origin && str_starts_with($origin, 'http://192.168.1.52')) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
