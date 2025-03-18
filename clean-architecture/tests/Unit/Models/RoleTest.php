<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Models\Role;

\describe('casts', function () {
    \it('casts string to RoleID', function () {
        $role = new Role;
        $role->id = RoleID::Admin->value;

        \expect($role->id)->toBe(RoleID::Admin->value);
    });
});
