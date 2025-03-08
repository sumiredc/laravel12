<?php

declare(strict_types=1);

use App\Consts\Role;
use App\ValueObjects\Role\RoleID;

test('RoleID:parse() correctly initializes from a given ULID', function (Role $role) {
    $RoleID = RoleID::parse($role);

    expect($role->value)->toBe($RoleID->value);
})
    ->with([
        'Admin role' => Role::Admin,
        'User role' => Role::User,
    ]);

test('RoleID::__toString() correctly initializes from a given ULID', function (Role $role) {
    $RoleID = RoleID::parse($role);

    expect($role->value)->toBe((string) $RoleID);
})
    ->with([
        'Admin role' => Role::Admin,
        'User role' => Role::User,
    ]);
