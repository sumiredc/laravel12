<?php

declare(strict_types=1);

use App\Domain\ValueObjects\OAuthToken;

\describe('__construct', function () {
    \it('initializes OAuthToken with the specified values', function () {
        $token = 'access-token';

        $result = new OAuthToken($token);

        \expect($result->value)->toBe($token);
    });
});

\describe('__toString', function () {
    \it('returns the token string when cast to string', function () {
        $token = 'access-token';

        $result = new OAuthToken($token);

        \expect((string) $result)->toBe($token);
    });
});

\describe('jsonSerialize', function () {
    \it('serializes to JSON as the token string', function () {
        $token = 'access-token';

        $result = new OAuthToken($token);

        \expect(\json_encode($result))->toBe(\json_encode($token));
    });
});
