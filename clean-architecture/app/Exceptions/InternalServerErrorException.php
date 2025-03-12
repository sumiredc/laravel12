<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class InternalServerErrorException extends Exception
{
    protected $message = 'Internal Server Error';

    protected $code = 500;
}
