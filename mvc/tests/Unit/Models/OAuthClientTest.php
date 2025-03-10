<?php

declare(strict_types=1);

use App\Models\OAuthClient;
use App\ValueObjects\User\UserID;

describe('OAuthClient model', function () {
    it('success to casts', function () {
        $userID = UserID::make();
        $model = new OAuthClient(['user_id' => $userID]);

        expect((string) $model->user_id)->toBe((string) $userID);
    });
});
