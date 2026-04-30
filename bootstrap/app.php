<?php

use App\Exceptions\MessageError;
use App\Http\Resources\MessageResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (MessageError $e) {
            return (new MessageResource([
                'status' => $e->statusCode,
                'message' => $e->errorMessage,
                'data' => $e->data,
            ]))
                ->response()
                ->setStatusCode($e->statusCode);
        });

        $exceptions->render(function (ValidationException $e) {
            $firstError = collect($e->errors())->first()[0];

            return (new MessageResource([
                'status' => 422,
                'message' => $firstError,
                'data' => null,
            ]))
                ->response()
                ->setStatusCode(422);
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            $class = class_basename($e->getModel());

            return (new MessageResource([
                'status' => 404,
                'message' => "{$class} not found.",
                'data' => null,
            ]))
                ->response()
                ->setStatusCode(404);
        });
    })->create();
