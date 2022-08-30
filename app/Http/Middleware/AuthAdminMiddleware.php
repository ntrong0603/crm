<?php

/**
 * Controller AuthAdminMiddelware
 *
 * @package    App\Http\Middleware
 * @subpackage AuthAdminMiddelware
 * @copyright  Copyright (c) 2019 RiverCrane! Corporation. All Rights Reserved.
 * @author     Le Trong<le.trong@rivercrane.com.vn>
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        } else {
            return redirect()->route('formLogin');
        }
    }
}
