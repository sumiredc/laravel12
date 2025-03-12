<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class UnauthorizedException extends Exception
{
    protected $message = 'Unauthorized';

    protected $code = 401;
}
