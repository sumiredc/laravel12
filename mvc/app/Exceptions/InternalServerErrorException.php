<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InternalServerErrorException extends AppError
{
    protected $message = 'An error occurred while processing your request. Please contact support if the issue persists.';

    protected $code = 500;
}
