<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class InvalidCredentialException extends Exception
{
    protected $message = 'Invalid authentication credentials.';

    protected $code = 401;
}
