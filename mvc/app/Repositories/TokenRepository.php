<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Consts\PersonalAccessTokenName;
use App\Models\User;
use Laravel\Passport\Client;

final class TokenRepository
{
    public function getOAuthClient(string $clientID): Client
    {
        return Client::find($clientID);
    }

    public function createUserAuthorizationToken(User $user): string
    {
        return $user->createToken(
            name: PersonalAccessTokenName::UserAuthorization->value,
        )
            ->accessToken;
    }

    public function deleteUserAuthorizationToken(User $user): void
    {
        $user->tokens()->delete();
    }
}
