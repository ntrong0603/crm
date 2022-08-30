<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class MallMiddleware
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
        $requestMall = $request->get('mall');
        if ($requestMall) {
            $mallCurrent = $requestMall;
        } else {
            if (!Session::has('mall') ) {
                $mallCurrent = 'biz';
            } else {
                $mallCurrent = session('mall');
            }
        }
        if(!in_array($mallCurrent, ['biz', 'df'])) {
            $mallCurrent = 'biz';
        }
        session(['mall' => $mallCurrent]);
        return $next($request);
    }
}
