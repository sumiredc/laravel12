<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class ForbiddenException extends Exception
{
    protected $message = 'You don’t have permission to access this endpoint.';

    protected $code = 403;
}
