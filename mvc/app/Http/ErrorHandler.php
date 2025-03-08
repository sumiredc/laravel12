<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\NotFoundException;
use App\Exceptions\ShouldntReportException;
use App\Exceptions\UnauthorizedException;
use App\Http\Resources\Error\ErrorResource;
use App\Http\Resources\Error\UnprocessableContentResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class ErrorHandler
{
    /**
     * An Exception class that does not disclose error messages.
     *
     * @var array<string,int>
     */
    private array $privateExceptions = [
        QueryException::class => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
    ];

    public function report(Exceptions $exceptions): void
    {
        $exceptions->dontReport([
            OAuthServerException::class,
        ]);
    }

    /**
     * @throws HttpResponseException
     */
    public function jsonResponse(Exceptions $exceptions): void
    {
        $exceptions->shouldRenderJsonWhen(static function (Request $request, Throwable $th): bool {
            if ($request->isJson()) {
                return true;
            }

            return $request->expectsJson();
        })
            ->render(function (Throwable $th, Request $request): ?JsonResponse {
                if (!$request->isJson()) {
                    return null;
                }

                $th = $this->hiddenPrivateException($th);
                $th = $this->mappingForAuthorization($th);
                $th = $this->mappingForNotFound($th, $request);
                $resource = $this->exceptionToJsonResource($th, $request);
                $statusCode = $this->statusCodeByException($th);

                return new JsonResponse($resource, $statusCode);
            });
    }

    /**
     * NOTE: Overwrite for default message.
     */
    private function mappingForAuthorization(Throwable $th)
    {
        if ($th instanceof AuthenticationException) {
            return new UnauthorizedException;
        }

        return $th;
    }

    /**
     * NOTE: Overwrite for default message.
     */
    private function mappingForNotFound(Throwable $th, Request $request): Throwable
    {
        if (
            $th instanceof NotFoundHttpException
            || $th instanceof ModelNotFoundException
        ) {
            Log::warning($th->getMessage(), ['path' => $request->uri()->value()]);
            $th = new NotFoundException(previous: $th);
        }

        return $th;
    }

    /**
     * NOTE: Replace it with ShouldntReport to prevent error logs from being output twice.
     */
    private function hiddenPrivateException(Throwable $th): Throwable
    {
        $statusCode = $this->privateExceptions[$th::class] ?? 0;
        if ($statusCode === 0) {
            return $th;
        }

        $statusText = JsonResponse::$statusTexts[$statusCode] ?? JsonResponse::$statusTexts[JsonResponse::HTTP_INTERNAL_SERVER_ERROR];

        return new ShouldntReportException($statusText, $statusCode, $th);
    }

    private function exceptionToJsonResource(Throwable $th): JsonResource
    {
        if ($th instanceof ValidationException) {
            return new UnprocessableContentResource($th->getMessage(), $th->errors());
        }

        return new ErrorResource($th->getMessage());
    }

    private function statusCodeByException(mixed $ex): int
    {
        $checkStatusCode = fn (int $code) => $code >= 100 && $code < 600;

        $methods = ['getCode', 'getStatus', 'getStatusCode'];

        foreach ($methods as $method) {
            if (!method_exists($ex, $method)) {
                continue;
            }

            $statusCode = intval($ex->$method());
            if ($checkStatusCode($statusCode)) {
                return $statusCode;
            }
        }

        $properties = ['status'];

        foreach ($properties as $property) {
            if (!property_exists($ex, $property)) {
                continue;
            }

            $statusCode = intval($ex->$property);
            if ($checkStatusCode($statusCode)) {
                return $statusCode;
            }
        }

        Log::warning('failed get to status code by Exception', [
            'exception' => $ex::class,
        ]);

        return JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}
