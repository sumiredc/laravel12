<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\OAuthToken;
use App\Domain\ValueObjects\UserID;
use App\UseCases\Auth\FirstSignInOutput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $user = new User(
            UserID::make(),
            RoleID::User,
            'sample name',
            'sample@xxx.xx'
        );
        $token = new OAuthToken('token');

        $result = new FirstSignInOutput($user, $token);

        \expect($result->user)->toBe($user);
        \expect($result->token)->toBe($token);
    });
});
