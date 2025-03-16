<?php

declare(strict_types=1);

use App\Domain\ValueObjects\OAuthTokenID;

\describe('parse', function () {
    \it('returns an OAuthTokenID when parse to string', function () {
        $tokenID = 'token-id';

        $result = OAuthTokenID::parse($tokenID);

        \expect($result->value)->toBe($tokenID);
    });
});

\describe('__toString', function () {
    \it('returns the token string when cast to string', function () {
        $tokenID = 'token-id';

        $result = OAuthTokenID::parse($tokenID);

        \expect((string) $result)->toBe($tokenID);
    });
});

\describe('jsonSerialize', function () {
    \it('serializes to JSON as the token string', function () {
        $tokenID = 'token-id';

        $result = OAuthTokenID::parse($tokenID);

        \expect(\json_encode($result))->toBe(\json_encode($tokenID));
    });
});
