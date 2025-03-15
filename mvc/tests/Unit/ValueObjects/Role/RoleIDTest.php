<?php

declare(strict_types=1);

use App\Consts\Role;
use App\ValueObjects\Role\RoleID;

\describe('parse', function () {
    \it('parses a Role into a RoleID', function (Role $role) {
        $result = RoleID::parse($role);

        \expect($result->value)->toBe($role->value);
    })
        ->with([
            'Admin role' => Role::Admin,
            'User role' => Role::User,
        ]);

});

\describe('__toString', function () {
    \it('converts RoleID to string correctly', function (Role $role) {
        $result = RoleID::parse($role);

        \expect((string) $result)->toBe($role->value);
    })
        ->with([
            'Admin role' => Role::Admin,
            'User role' => Role::User,
        ]);
});
