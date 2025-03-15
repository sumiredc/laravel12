<?php

declare(strict_types=1);

namespace App\Http\ExceptionHandler;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class JsonResolver
{
    /** @return callable(Request $request, Throwable $th) */
    public function shouldReturnJsonResponse(): callable
    {
        return fn (Request $request, Throwable $th): bool => $this->isEnableJson($request);
    }

    /** @return callable(Throwable $th, Request $request) */
    public function createJsonExceptionRenderer(): callable
    {
        return function (Throwable $th, Request $request): ?JsonResponse {
            if (!$this->isEnableJson($request)) {
                return null;
            }

            $mapper = app(ExceptionMapper::class);

            $th = $mapper->hiddenPrivateException($th);
            $th = $mapper->mapForAuthorization($th);
            $th = $mapper->mapForNotFound($th, $request);
            $resource = $mapper->exceptionToJsonResource($th, $request);
            $statusCode = $mapper->getStatusCode($th);

            return new JsonResponse($resource, $statusCode);
        };
    }

    private function isEnableJson(Request $request): bool
    {
        if ($request->isJson()) {
            return true;
        }

        return $request->expectsJson();
    }
}
