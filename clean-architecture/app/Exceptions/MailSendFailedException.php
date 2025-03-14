<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class MailSendFailedException extends Exception
{
    protected $message = 'Failed to send email.';

    protected $code = 503;
}
