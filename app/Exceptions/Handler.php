<?php

namespace App\Exceptions;

use App\Enums\ToastifyStatus;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Predis\Configuration\Option\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() {
    }

    public function render($request, Throwable $e) {
        $response = parent::render($request, $e);
        $status = $response->status();
        return match ($status) {
            404 => redirect('dashboard/404'),
            403 => redirect('dashboard/403'),
            //500 => back()->with(['message' => 'Nastala nečekaná chyba!', 'status' => ToastifyStatus::ERROR]),
            default => $response
        };
    }
}
