<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\Password;
use App\Exceptions\InternalServerErrorException;

interface MailRepositoryInterface
{
    /** @return Result<null,InternalServerErrorException|MailSendFailedException> */
    public function sendInitialPassword(User $user, Password $password): Result;
}
