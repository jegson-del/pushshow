<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        switch ($guards) {
            case 'api':
                if (Auth::guard($guard)->check()) {
                    return $next($request);
                }
                break;
            case 'admin':
                if (Auth::guard($guard)->check()) {
                    return $next($request);
                }
                break;
            case 'subscriber':
                if (Auth::guard($guard)->check()) {
                    return $next($request);
                }
                break;
            default:

                break;
        }

        return $next($request);
    }
}
