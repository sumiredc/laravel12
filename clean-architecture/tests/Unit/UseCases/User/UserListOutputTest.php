<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserID;
use App\UseCases\User\UserListOutput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $users = \array_map(fn ($i) => new User(
            UserID::make(),
            RoleID::User,
            "sample name $i",
            "sample$i@xxx.xx"
        ), \range(0, 10));

        $result = new UserListOutput($users);

        \expect($result->users)->toBe($users);
    });
});
