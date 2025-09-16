<?php

namespace App\Exceptions;

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

        // invalid input
        $this->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'error'   => 'Invalid input',
                'message' => $e->errors(),
            ],400); // bad request
        });

        // model not found
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'error'   => 'Resource not found',
                'model'   => $e->getModel(),
                'message' => $e->getMessage(),
            ], 404); // not found
        });

        // Catch generic NotFoundHttpException (like wrong route or converted model not found)
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            $previous = $e->getPrevious(); // check if model not found
            
            if ($previous instanceof ModelNotFoundException)
            {
                return response()->json([
                    'error'   => class_basename($previous->getModel()) . ' not found',
                    'id'      => $previous->getIds()[0] ?? null,
                    'message' => $e->getMessage(),
                ],404);
            }
 
            // fall back for wrong route
            return response()->json([
                'error'   => 'Not Found',
                'message' => 'The requested resource could not be found.',
            ], 404);
        });
    }
    
}
