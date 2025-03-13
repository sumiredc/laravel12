<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class EmailAlreadyTakenException extends Exception
{
    protected $message = 'The email has already been taken.';

    protected $code = 409;
}
