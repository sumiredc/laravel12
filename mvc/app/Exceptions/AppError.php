<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

abstract class AppError extends Exception
{
    public function __construct(?string $message = '', ?int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message ?: $this->message ?? '',
            code: $code ?: $this->code ?? 0,
            previous: $previous
        );
    }
}
