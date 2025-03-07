<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;

final class NotFoundException extends AppError implements ShouldntReport
{
    protected $message = 'The endpoint you are looking for does not exist.';

    protected $code = 404;
}
