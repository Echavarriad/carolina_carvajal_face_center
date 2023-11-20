<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin

{

    /**

     * @param $request

     * @param Closure $next

     * @param null $guard

     * @return \Illuminate\Http\RedirectResponse

     */

    public function handle($request, Closure $next, $guard = null)

    {

        if (!Auth::guard('admin')->check()) {

            return redirect()->route('admin.login');

        }

        

        return $next($request);

    }

}

