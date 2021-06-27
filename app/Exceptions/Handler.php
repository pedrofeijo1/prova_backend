<?php

namespace App\Exceptions;

use App\Http\Services\ResponseService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    private $responseService;

    public function __construct(Container $container, ResponseService $responseService)
    {
        $this->responseService = $responseService;

        parent::__construct($container);
    }

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (
            $e instanceof ExpectsJsonException ||
            $e instanceof MethodNotAllowedHttpException
        ) {
            return $this->responseService->badRequest($e->getMessage());

        } else if ($e instanceof UnauthenticatedException) {
            return $this->responseService->unauthorized($e->getMessage());

        } else if ($e instanceof NotFoundHttpException) {
            return $this->responseService->notFound('Not found.');

        } else if ($e instanceof ValidationException || \App::environment('local')) {
            return parent::render($request, $e);

        }

        return $this->responseService->serverError('Ops, some errors occurred try again later!');
    }
}
