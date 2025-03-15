<?php

declare(strict_types=1);

use App\Models\OAuthClient;
use App\ValueObjects\User\UserID;

\describe('casts', function () {
    \it('successfully casts user_id', function () {
        $userID = UserID::make();
        $model = new OAuthClient(['user_id' => $userID]);

        \expect((string) $model->user_id)->toBe((string) $userID);
    });
});
