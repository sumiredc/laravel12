<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

final class FirstSignInInput
{
    public function __construct(
        public readonly string $loginID,
        public readonly string $password,
        public readonly string $newPassword
    ) {}
}
