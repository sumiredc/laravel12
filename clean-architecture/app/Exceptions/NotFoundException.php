<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class NotFoundException extends Exception
{
    protected $message = 'Not Found';

    protected $code = 404;
}
