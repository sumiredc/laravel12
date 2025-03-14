<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Entities\User;

final class UserListOutput
{
    /** @param array<User> $users */
    public function __construct(
        public readonly array $users
    ) {}
}
