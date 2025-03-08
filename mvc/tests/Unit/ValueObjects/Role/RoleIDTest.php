<?php

declare(strict_types=1);

use App\Consts\Role;
use App\ValueObjects\Role\RoleID;

describe('RoleID', function () {
    it('parse() correctly initializes from a given ULID', function (Role $role) {
        $result = RoleID::parse($role);

        expect($result->value)->toBe($role->value);
    })
        ->with([
            'Admin role' => Role::Admin,
            'User role' => Role::User,
        ]);

    it('__toString() correctly initializes from a given ULID', function (Role $role) {
        $result = RoleID::parse($role);

        expect((string) $result)->toBe($role->value);
    })
        ->with([
            'Admin role' => Role::Admin,
            'User role' => Role::User,
        ]);
});
