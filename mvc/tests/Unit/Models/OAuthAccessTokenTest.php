<?php

declare(strict_types=1);

use App\Models\OAuthAccessToken;
use App\ValueObjects\User\UserID;

describe('OAuthAccessToken model', function () {
    it('success to casts', function () {
        $userID = UserID::make();
        $model = new OAuthAccessToken(['user_id' => $userID]);

        expect((string) $model->user_id)->toBe((string) $userID);
    });
});
