<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserID;
use App\Http\Resources\User\UserResource;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $user = new User(UserID::make(), RoleID::User, 'sample name', 'email@xxx.xx');

        $result = new UserResource($user);

        \expect($result->resource['user'])->toBe($user);
    });
});
