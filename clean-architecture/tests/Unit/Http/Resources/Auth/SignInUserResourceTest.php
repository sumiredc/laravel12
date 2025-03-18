<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\OAuthToken;
use App\Domain\ValueObjects\UserID;
use App\Http\Resources\Auth\SignInUserResource;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $user = new User(UserID::make(), RoleID::Admin, 'sample name', 'email@xxx.xx');
        $token = new OAuthToken('token-value');

        $result = new SignInUserResource($user, $token);

        \expect($result->resource['user'])->toBe($user);
        \expect($result->resource['token'])->toBe($token);
    });
});
