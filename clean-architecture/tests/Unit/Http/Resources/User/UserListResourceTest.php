<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserID;
use App\Http\Resources\User\UserListResource;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $users = \array_map(function ($i) {
            return new User(UserID::make(), RoleID::User, "sample name{$i}", "email{$i}@xxx.xx");
        }, \range(0, 10));

        $result = new UserListResource($users);

        \expect($result->resource['users'])->toBe($users);
    });
});
