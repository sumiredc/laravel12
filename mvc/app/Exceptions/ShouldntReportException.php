<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;
use Throwable;

final class ShouldntReportException extends Exception implements ShouldntReport
{
    public function __construct(string $message, int $code, Throwable $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}
