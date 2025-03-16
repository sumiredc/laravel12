<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\AuthUser;
use App\Domain\Entities\OAuthPersonalAccessToken;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;

\describe('__construct', function () {
    \it('initializes AuthUser with the specified values', function () {
        $token = new OAuthPersonalAccessToken(
            tokenID: OAuthTokenID::parse('token-id'),
            clientID: OAuthClientID::parse('c570e275-9eac-41d0-b45d-9c7e70dec5f3')->getValue()
        );

        $userID = UserID::make();
        $roleID = RoleID::Admin;
        $name = 'sample name';
        $email = 'sample@xxx.xx';

        $result = new AuthUser(
            userID: $userID,
            roleID: $roleID,
            name: $name,
            email: $email,
            personalAccessToken: $token,
        );

        \expect((string) $result->userID)->toBe((string) $userID);
        \expect($result->roleID)->toBe($roleID);
        \expect($result->name)->toBe($name);
        \expect($result->email)->toBe($email);
        \expect((string) $result->personalAccessToken->tokenID)->toBe((string) $token->tokenID);
        \expect((string) $result->personalAccessToken->clientID)->toBe((string) $token->clientID);
    });
});
