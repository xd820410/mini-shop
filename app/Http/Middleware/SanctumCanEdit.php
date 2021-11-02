<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SanctumCanEdit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->tokenCan('edit')) {
            return $next($request);
        }

        $errorMessage = [
            'message' => "Unauthenticated.",
        ];

        return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
    }
}
