<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

interface TokenRepositoryInterface
{
    public function createUserAuthorizationToken(User $user): string;

    public function revokeUserAuthorizationToken(User $user): void;

    public function deleteUserAuthorizationToken(User $user): void;
}
