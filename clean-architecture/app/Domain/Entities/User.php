<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Consts\RoleID;
use App\Domain\ValueObjects\UserID;

final class User
{
    public function __construct(
        public readonly UserID $userID,
        public readonly RoleID $roleID,
        public readonly string $name,
        public readonly string $email,
    ) {}
}
