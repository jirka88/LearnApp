<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;

class is_admin {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        if (auth()->check() && (auth()->user()->role_id == UserRoles::ADMIN || auth()->user()->role_id == UserRoles::OPERATOR)) {
            return $next($request);
        } else {
            return to_route('dashboard');
        }
    }
}
