<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\User;
use App\ValueObjects\Role\RoleID;

describe('Role::isAdmin()', function () {
    it('valid admin role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::Admin)]);

        expect(Role::isAdmin($user))->toBeTrue();
    });

    it('invalid admin role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::User)]);

        expect(Role::isAdmin($user))->toBeFalse();
    });
});

describe('Role::isUser()', function () {
    it('valid user role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::User)]);

        expect(Role::isUser($user))->toBeTrue();
    });

    it('invalid user role', function () {
        $user = new User(['role_id' => RoleID::parse(Role::Admin)]);

        expect(Role::isUser($user))->toBeFalse();
    });
});
