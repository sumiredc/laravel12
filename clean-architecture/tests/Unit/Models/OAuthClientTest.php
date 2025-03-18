<?php

declare(strict_types=1);

use App\Domain\ValueObjects\UserID;
use App\Models\OAuthClient;

\describe('casts', function () {
    \it('casts to string from user_id', function () {
        $userID = UserID::make();
        $client = new OAuthClient;
        $client->user_id = $userID;

        \expect($client->user_id)->toBe((string) $userID);
    });
});
