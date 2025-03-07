<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;

final class UnauthorizedException extends AppError implements ShouldntReport
{
    protected $message = 'Missing or invalid authentication token.';

    protected $code = 401;
}
