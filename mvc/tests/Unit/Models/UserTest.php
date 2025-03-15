<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\User;
use App\ValueObjects\Role\RoleID;
use App\ValueObjects\User\UserID;

\describe('casts', function () {
    \it('successfully casts id and role_id', function () {
        $userID = UserID::make();
        $roleID = RoleID::parse(Role::User);
        $model = new User(['id' => $userID, 'role_id' => $roleID]);

        \expect((string) $model->id)->toBe((string) $userID);
        \expect((string) $model->role_id)->toBe((string) $roleID);
    });
});
