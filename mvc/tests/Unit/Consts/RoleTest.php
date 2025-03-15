<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\User;
use App\ValueObjects\Role\RoleID;

\describe('isAdmin', function () {
    \it('returns true for an admin role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::Admin)]);

        \expect(Role::isAdmin($user))->toBeTrue();
    });

    \it('returns false for a non-admin role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::User)]);

        \expect(Role::isAdmin($user))->toBeFalse();
    });
});

\describe('isUser', function () {
    \it('returns true for a user role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::User)]);

        \expect(Role::isUser($user))->toBeTrue();
    });

    \it('returns false for a non-user role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::Admin)]);

        \expect(Role::isUser($user))->toBeFalse();
    });
});
