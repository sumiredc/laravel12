<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\ValueObjects\UserID;

final class UserUpdateInput
{
    public function __construct(
        public readonly UserID $userID,
        public readonly string $name,
        public readonly string $email,
    ) {}
}
