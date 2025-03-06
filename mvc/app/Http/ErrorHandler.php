<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\UnprocessableContentException;
use App\Http\Resources\Error\ErrorResource;
use App\Http\Resources\Error\UnprocessableContentResource;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class ErrorHandler
{
    /**
     * @throws HttpResponseException
     */
    public function json(Exceptions $exceptions): void
    {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $th) {
            if ($request->isJson()) {
                return true;
            }

            return $request->expectsJson();
        });

        $exceptions->render(function (Throwable $ex, Request $request): ?JsonResponse {
            if (!$request->isJson()) {
                return null;
            }

            $resource = match ($ex::class) {
                UnprocessableContentException::class => (static function () use ($ex): UnprocessableContentResource {
                    /** @var UnprocessableContentException $ex */
                    return new UnprocessableContentResource($ex->getMessage(), $ex->errors);
                })(),
                default => new ErrorResource($ex->getMessage()),
            };

            return new JsonResponse($resource, $ex->getCode());
        });
    }
}
