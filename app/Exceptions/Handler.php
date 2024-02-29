<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $model = app($exception->getModel());
            // message with the model name
            $message = class_basename($model). ' not found';
            return \response()->json([
                'message' => $message
            ], 404);
        }

        // Check if the exception is an instance of AccessDeniedHttpException
        if ($exception instanceof AuthorizationException) {
            // Return a response with your custom message
            return \response()->json([
                'message' => 'The action is unauthorized',
            ], 403);
        }
        
        if($exception instanceof ValidationException){
            $errors = $exception->validator->errors()->getMessages();
            return \response()->json([
                'message' => 'Validation error',
                'errors' => $errors
            ], 403);
        }

        return parent::render($request, $exception);
    }
}
