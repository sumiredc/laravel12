<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

final class ForbiddenException extends Exception implements ShouldntReport
{
    protected $message = 'You don’t have permission to access this endpoint.';

    protected $code = 403;
}
