<?php

declare(strict_types=1);

namespace App\UseCases\User;

final readonly class UserCreateInput
{
    public function __construct(
        public readonly string $name,
        public readonly string $email
    ) {}
}
