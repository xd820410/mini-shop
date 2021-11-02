<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class Manager
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
        if (Auth::check()) {
            $permissions = Auth::user()->permissions;
            foreach ($permissions as $permission) {
                //是管理員就走原流程
                if ($permission['right'] == Permission::administrator) {
                    return $next($request);
                }
            }
        }

        return redirect('/');
    }
}
