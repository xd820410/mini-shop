<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use Illuminate\Support\Facades\App;

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
            //dd($request->session()->get('cart'));
            //method inject
            App::call([new CartService, 'mergeSessionCart'], ['userId' => Auth::user()->id, 'sessionCart' => $request->session()->get('cart')]);
            $request->session()->forget('cart');
        }
        return $next($request);
    }
}
