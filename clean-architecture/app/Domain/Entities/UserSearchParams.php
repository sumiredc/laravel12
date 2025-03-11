<?php

declare(strict_types=1);

namespace App\Domain\Entities;

final class UserSearchParams
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly int $offset,
        public readonly int $limit
    ) {}
}
