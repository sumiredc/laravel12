<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;
use Throwable;

final class UnprocessableContentException extends Exception implements ShouldntReport
{
    public function __construct(
        public readonly array $errors,
        string $message = 'Unable to process the request due to semantic errors.',
        int $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
