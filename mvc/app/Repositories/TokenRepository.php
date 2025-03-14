<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Consts\PersonalAccessTokenName;
use App\Models\User;

final class TokenRepository implements TokenRepositoryInterface
{
    public function createUserAuthorizationToken(User $user): string
    {
        return $user->createToken(
            name: PersonalAccessTokenName::UserAuthorization->value,
        )
            ->accessToken;
    }

    public function revokeUserAuthorizationToken(User $user): void
    {
        $user->token()->revoke();
    }

    public function deleteUserAuthorizationToken(User $user): void
    {
        $user->tokens()->delete();
    }
}
