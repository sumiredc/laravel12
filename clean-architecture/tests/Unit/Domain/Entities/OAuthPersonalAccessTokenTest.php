<?php

declare(strict_types=1);

use App\Domain\Entities\OAuthPersonalAccessToken;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;

\describe('__construct', function () {
    \it('initializes OAuthPersonalAccessToken with the specified values', function () {
        $tokenID = OAuthTokenID::parse('token-id');
        $clientID = OAuthClientID::parse('c570e275-9eac-41d0-b45d-9c7e70dec5f3')->getValue();

        $result = new OAuthPersonalAccessToken(
            tokenID: $tokenID,
            clientID: $clientID,
        );

        \expect((string) $result->tokenID)->toBe((string) $tokenID);
        \expect((string) $result->clientID)->toBe((string) $clientID);
    });
});
