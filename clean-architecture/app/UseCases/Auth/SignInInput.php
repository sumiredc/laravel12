<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

final class SignInInput
{
    public function __construct(
        public readonly string $loginID,
        public readonly string $password
    ) {}
}
