<?php

namespace App\Http\Concerns;

use App\Exceptions\CustomException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Trait HasExceptionApiResponse
 *
 * @package App\Http\Concerns
 */
trait HasExceptionApiResponse
{
    use HasApiResponse;

    /**
     * Response Exception
     *
     * @param Request   $request   Request
     * @param Throwable $exception Throwable\Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseException($request, Throwable $exception)
    {
        switch (true) {
            case $exception instanceof AuthenticationException:
            case $exception instanceof ValidationException:
                return $this->responseError(
                    Arr::flatten([
                        __('error.validation_failed'),
                        $exception->errors()
                    ]),
                    null,
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            case $exception instanceof NotFoundHttpException:
            case $exception instanceof ModelNotFoundException:
                return $this->responseError(__('error.404_not_found'), null, Response::HTTP_NOT_FOUND);
            case $exception instanceof CustomException:
                return $this->responseError($exception);
            case $exception instanceof HttpException:
                return $this->responseError($exception->getMessage(), null, $exception->getStatusCode());
            case $exception instanceof AuthorizationException:
                return $this->responseError($exception->getMessage(), null, Response::HTTP_FORBIDDEN);
            case $exception instanceof MethodNotAllowedHttpException:
                return $this->responseError($exception->getMessage(), null, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if (config('app.debug')) {
            return $this->responseError(
                __('error.internal_error'),
                [
                    'message' => $exception->getMessage(),
                    'file'    => $exception->getFile() . ':' . $exception->getLine(),
                    'trace'   => explode(PHP_EOL, $exception->getTraceAsString()),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->responseError(__('error.internal_error'), null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
