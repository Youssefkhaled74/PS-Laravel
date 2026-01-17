<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Validation errors
        if ($e instanceof ValidationException) {
            $errors = $e->errors();
            $message = trans('api.validation_failed') ?: trans('validation.failed');
            $payload = [
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => $errors,
                'meta' => null,
            ];
            return response()->json($payload, 422);
        }

        // Authentication errors
        if ($e instanceof AuthenticationException) {
            $message = trans('api.unauthorized') ?: trans('auth.unauthenticated');
            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => null,
                'meta' => null,
            ], 401);
        }

        // Authorization errors
        if ($e instanceof AuthorizationException) {
            $message = trans('api.forbidden') ?: trans('auth.unauthorized');
            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => null,
                'meta' => null,
            ], 403);
        }

        // Model not found -> 404
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            $message = trans('api.not_found') ?: 'Not found';
            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => null,
                'meta' => null,
            ], 404);
        }

        return parent::render($request, $e);
    }
}
