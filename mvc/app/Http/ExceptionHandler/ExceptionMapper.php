<?php

declare(strict_types=1);

namespace App\Http\ExceptionHandler;

use App\Exceptions\NotFoundException;
use App\Exceptions\ShouldntReportException;
use App\Exceptions\UnauthorizedException;
use App\Http\Resources\Error\ErrorResource;
use App\Http\Resources\Error\UnprocessableContentResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

use function intval;
use function method_exists;
use function property_exists;

final class ExceptionMapper
{
    /**
     * An Exception class that does not disclose error messages.
     *
     * @var array<string,int>
     */
    private array $privateExceptions = [
        QueryException::class => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
    ];

    /**
     * NOTE: Overwrite for default message.
     */
    public function mapForAuthorization(Throwable $th)
    {
        if ($th instanceof AuthenticationException) {
            return new UnauthorizedException;
        }

        return $th;
    }

    /**
     * NOTE: Overwrite for default message.
     */
    public function mapForNotFound(Throwable $th, Request $request): Throwable
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
    public function hiddenPrivateException(Throwable $th): Throwable
    {
        $statusCode = $this->privateExceptions[$th::class] ?? 0;
        if ($statusCode === 0) {
            return $th;
        }

        $statusText = JsonResponse::$statusTexts[$statusCode];

        return new ShouldntReportException($statusText, $statusCode, $th);
    }

    public function exceptionToJsonResource(Throwable $th): JsonResource
    {
        if ($th instanceof ValidationException) {
            return new UnprocessableContentResource($th->getMessage(), $th->errors());
        }

        return new ErrorResource($th->getMessage());
    }

    public function getStatusCode(mixed $ex): int
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
