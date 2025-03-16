<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\AuthUser;
use App\Domain\Entities\OAuthPersonalAccessToken;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;
use App\UseCases\Auth\SignOutInput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $authUser = new AuthUser(
            UserID::make(),
            RoleID::Admin,
            'sample name',
            'sample@xxx.xx',
            new OAuthPersonalAccessToken(
                OAuthTokenID::parse('bbb'),
                OAuthClientID::parse('8934681e-08eb-4f86-aa06-585a37509885')->getValue()
            )
        );

        $result = new SignOutInput($authUser);

        \expect($result->user)->toBe($authUser);
    });
});
