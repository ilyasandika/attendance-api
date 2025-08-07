<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => __('errorMessages.validation_error'),
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof AuthenticationException) {

            return response()->json([
                'success' => false,
                'message' => __('errorMessages.unauthorized'),
                'errors' => [$exception->getMessage()],
            ], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => __('errorMessages.forbidden'),
                'errors' => [$exception->getMessage()],
            ], 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => __('errorMessages.not_found'),
                'errors' => [$exception->getMessage()],
            ], 404);
        }

        if ($exception instanceof FieldInUseException) {
            return response()->json([
                'success' => false,
                'message' => __('errorMessages.field_in_use'),
                'errors' => [$exception->getMessage()],
            ], 409);
        }
        return response()->json([
            'success' => false,
            'message' => __('errorMessages.server_error'),
            'errors' => [$exception->getMessage()],
        ], 500);
    }
}
