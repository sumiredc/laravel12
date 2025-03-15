<?php

declare(strict_types=1);

namespace App\Http\ExceptionHandler;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\HttpResponseException;
use League\OAuth2\Server\Exception\OAuthServerException;

use function app;

final class Handler
{
    public function __construct(private readonly Exceptions $exceptions) {}

    public function report(): self
    {
        $this->exceptions->dontReport([
            OAuthServerException::class,
        ]);

        return $this;
    }

    /**
     * @throws HttpResponseException
     */
    public function createJsonResponse(): self
    {
        $resolver = app(JsonResolver::class);

        $this->exceptions
            ->shouldRenderJsonWhen($resolver->shouldReturnJsonResponse())
            ->render($resolver->createJsonExceptionRenderer());

        return $this;
    }
}
