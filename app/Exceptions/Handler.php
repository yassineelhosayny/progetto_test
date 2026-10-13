<?php


namespace App\Exceptions;
use Illuminate\Support\Facades\Log;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    // ...existing code...

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            Log::error('Forbidden exception', [
                'user_id' => optional(Auth::user())->id,
                'url' => $request->fullUrl(),
                'message' => $exception->getMessage(),
            ]);
        }
        return parent::render($request, $exception);
    }
}
