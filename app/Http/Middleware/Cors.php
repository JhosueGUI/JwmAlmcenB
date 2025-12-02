<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        // Si es un preflight OPTIONS request, respondemos inmediatamente
        if ($request->getMethod() === "OPTIONS") {
            return response('')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
