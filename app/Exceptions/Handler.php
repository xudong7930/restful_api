<?php

namespace App\Exceptions;

use App\Acme\Traint\ApiResponser;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        } elseif ($exception instanceof ModelNotFoundException) {
            $modelName = $exception->getModel();
            return $this->errorRespond("Model {$modelName} does not exist", 404);
        } elseif ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        } elseif ($exception instanceof AuthorizationException) {
            return $this->errorRespond($exception->getMessage(), 403);
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->errorRespond('URL Not Found!', 404);
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorRespond("Request method not allowd", 405);
        } elseif ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInputs($request->input());
        } elseif ($exception instanceof HttpException) {
            return $this->errorRespond($exception->getMessage(), $exception->getStatusCode());
        } elseif ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorRespond('Cannot remove this resource, it is related with any other resource', 409);
            }
        }

        if (!config('app.debug')) {
            return parent::render($request, $exception);
        }
        
        return $this->errorRespond($exception->getMessage(), 500);
    }

    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        $errors = $exception->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        return $this->errorRespond($errors, 422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return response()->json(['error' => 'unauthencated!'], 401);

        if ($request->expectsJson()) {
            return response()->json(['error' => 'unauthencated!'], 401);
        }
    }

    protected function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
