<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCustomCORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Allow all origins
         header('Access-Control-Allow-Origin: *');  // Or replace '*' with a specific domain if needed
         header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
         header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
         header('Access-Control-Expose-Headers: Content-Type, Authorization, X-Requested-With');
 
         // Handle OPTIONS method (preflight)
         if ($request->getMethod() == "OPTIONS") {
             return response()->json([], 200);
         }
        return $next($request);
    }
}
