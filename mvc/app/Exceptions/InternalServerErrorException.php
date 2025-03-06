<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class InternalServerErrorException extends Exception
{
    protected $message = 'An error occurred while processing your request. Please contact support if the issue persists.';

    protected $code = 500;
}
