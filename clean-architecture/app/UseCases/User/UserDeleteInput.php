<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\ValueObjects\UserID;

final class UserDeleteInput
{
    public function __construct(public readonly UserID $userID) {}
}
