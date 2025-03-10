<?php

declare(strict_types=1);

use App\Models\OAuthAuthCode;
use App\ValueObjects\User\UserID;

describe('OAuthAuthCode model', function () {
    it('success to casts', function () {
        $userID = UserID::make();
        $model = new OAuthAuthCode(['user_id' => $userID]);

        expect((string) $model->user_id)->toBe((string) $userID);
    });
});
