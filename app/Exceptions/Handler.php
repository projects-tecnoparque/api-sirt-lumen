<?php

namespace App\Exceptions;

use App\Http\Responses\JsonApiValidationErrorResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return $this->handleException($request, $exception);
    }

    public function handleException($request, Throwable $exception)
    {

        if ($exception instanceof ValidationException) {
            return $this->invalidJson($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse(
                title: "Unauthorized",
                detail: $exception->getMessage(),
                status: Response::HTTP_FORBIDDEN
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse(
                title: "Method Not Allowed",
                detail: $exception->getMessage(),
                status: Response::HTTP_METHOD_NOT_ALLOWED
            );
        }
        if ($exception instanceof BadRequestException) {
            throw new JsonApi\BadRequestHttpException($exception->getMessage());
        }

        if ($exception instanceof NotFoundHttpException) {
            throw new JsonApi\NotFoundHttpException;
        }

        if ($exception instanceof \InvalidArgumentException) {
            return $this->errorResponse(
                title: "InvalidArgumentException",
                detail: $exception->getMessage(),
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse(
                title: "Model Not Found Exception",
                detail: "Does not exits any instance of {$model} with given id",
                status: Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse(
                title: "Http Exception",
                detail: $exception->getMessage(),
                status: $exception->getStatusCode()
            );
        }

        if ($exception instanceof QueryException) {
            return $this->errorResponse(
                title: "Query Exception",
                detail: $exception->getMessage(),
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse(
            title: 'Falla inesperada',
            detail: 'Falla inesperada. Intente luego',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    protected function invalidJson($request, ValidationException $exception): JsonApiValidationErrorResponse
    {
        return new JsonApiValidationErrorResponse($exception);
    }

    protected function errorResponse($title = '', $detail = '', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            [
                'errors' => [
                    'title' => $title,
                    'detail' => $detail,
                    'status' => (string) $status
                ]
            ]
        ], (int) $status);
    }
}
