<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserID;
use App\UseCases\User\UserGetOutput;

\describe('__construct', function () {
    \it('initializes this with the specified values', function () {
        $user = new User(UserID::make(), RoleID::User, 'sample name', 'sample@xxx.xx');

        $result = new UserGetOutput($user);

        \expect($result->user)->toBe($user);
    });
});
