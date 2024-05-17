<?php

namespace App\Http\Middleware;

use App\Enums\ToastifyStatus;
use Closure;
use Illuminate\Http\Request;

class ExceptionHandlingMiddleware
{
    public function handle(Request $request, Closure $next) {
        try{
            return $next($request);
        }
        catch(\Throwable $th) {
            return response()->with(['status' => ToastifyStatus::ERROR, 'message' => 'Nastala nečekaná chyba!']);
        }
    }
}
