<?php

declare(strict_types=1);

use App\Domain\ValueObjects\UserID;
use App\Models\OAuthAccessToken;

\describe('casts', function () {
    \it('casts to string from user_id', function () {
        $userID = UserID::make();
        $token = new OAuthAccessToken;
        $token->user_id = $userID;

        \expect($token->user_id)->toBe((string) $userID);
    });
});
