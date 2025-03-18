<?php

declare(strict_types=1);

use App\Domain\ValueObjects\UserID;
use App\Models\OAuthAuthCode;

\describe('casts', function () {
    \it('casts to string from user_id', function () {
        $userID = UserID::make();
        $authCode = new OAuthAuthCode;
        $authCode->user_id = $userID;

        \expect($authCode->user_id)->toBe((string) $userID);
    });
});
