<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;

final class InvalidCredentialException extends AppError implements ShouldntReport
{
    protected $message = 'Invalid authentication credentials.';

    protected $code = 401;
}
