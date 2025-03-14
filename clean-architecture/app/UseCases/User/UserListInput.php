<?php

declare(strict_types=1);

namespace App\UseCases\User;

final class UserListInput
{
    public function __construct(
        public readonly int $offset,
        public readonly int $limit,
        public readonly string $name,
        public readonly string $email
    ) {}
}
