<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            if($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'code' => 401, 
                    'message' => 'Unauthorized',
                    'error' => 'Unauthorized'
                ], 401);
            }

            //yang ini jika di perlukan
            if ($exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError) {
                return response()->json([
                    'data' => 1
                ], 400);
            }
            // batasnya

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json([
                    'code' => 404, 
                    'message' => 'Not Found',
                    'error' => 'Not Found',
                ], 404);
            }

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $modelClass = explode('\\', $exception->getModel());
                return response()->json([
                    'code' => 404, 
                    'message' => end($modelClass) . 'Not Found',
                    'error' => end($modelClass) . ' Not Found',
                ], 404);
            }

        }
        return parent::render($request, $exception);
    }
}
