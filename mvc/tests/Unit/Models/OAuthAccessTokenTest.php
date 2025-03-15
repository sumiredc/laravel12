<?php

declare(strict_types=1);

use App\Models\OAuthAccessToken;
use App\ValueObjects\User\UserID;

\describe('casts', function () {
    \it('successfully casts user_id', function () {
        $userID = UserID::make();
        $model = new OAuthAccessToken(['user_id' => $userID]);

        \expect((string) $model->user_id)->toBe((string) $userID);
    });
});
