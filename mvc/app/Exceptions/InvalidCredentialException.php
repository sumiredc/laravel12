<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidCredentialException extends AppError
{
    protected $message = 'Invalid authentication credentials.';

    protected $code = 401;
}
