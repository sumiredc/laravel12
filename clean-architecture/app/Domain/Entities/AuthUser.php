<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\UserID;

final class AuthUser
{
    public function __construct(
        public readonly UserID $userID,
        public readonly string $name,
        public readonly string $email,
        public readonly OAuthPersonalAccessToken $personalAccessToken,
        private readonly string $hashedPassword,
    ) {}

    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
