<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\MailRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\Password;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\MailSendFailedException;
use App\Mail\InitialPasswordMail;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class MailRepository implements MailRepositoryInterface
{
    public function sendInitialPassword(User $user, Password $password): Result
    {
        $template = new InitialPasswordMail($password->plain);
        try {
            if (is_null(Mail::to($user)->send($template))) {
                return Result::err(new MailSendFailedException);
            }

            return Result::ok(null);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }
}
