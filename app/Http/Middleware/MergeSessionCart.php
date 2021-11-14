<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MergeSessionCart
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
        if ($request->session()->has('cart') && !empty($request->session()->get('cart'))) {
            dd($request->session()->get('cart'));
        }
        return $next($request);
    }
}
