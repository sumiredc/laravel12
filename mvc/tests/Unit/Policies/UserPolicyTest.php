<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\User;
use App\Policies\UserPolicy;
use App\ValueObjects\Role\RoleID;
use App\ValueObjects\User\UserID;

\describe('list', function () {
    \it('allows admin to list users', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->list($auth, $user);
        \expect($response->allowed())->toBeTrue();
    });

    \it('denies normal user from listing users', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::User)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->list($auth, $user);
        \expect($response->denied())->toBeTrue();
    });
});

\describe('create', function () {
    \it('allows admin to create users', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $policy = new UserPolicy;

        $response = $policy->create($auth);
        \expect($response->allowed())->toBeTrue();
    });

    \it('denies normal user from creating users', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::User)]);
        $policy = new UserPolicy;

        $response = $policy->create($auth);
        \expect($response->denied())->toBeTrue();
    });
});

\describe('get', function () {
    \it('allows admin to get any user', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->get($auth, $user);
        \expect($response->allowed())->toBeTrue();
    });

    \it('allows a user to get their own data', function () {
        $userID = UserID::make();
        $auth = new User([
            'id' => $userID,
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => $userID]);
        $policy = new UserPolicy;

        $response = $policy->get($auth, $user);
        \expect($response->allowed())->toBeTrue();
    });

    \it('denies normal user from getting another user', function () {
        $auth = new User([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => UserID::make()]);
        $policy = new UserPolicy;

        $response = $policy->get($auth, $user);
        \expect($response->denied())->toBeTrue();
    });
});

\describe('update', function () {
    \it('allows admin to update any user', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->update($auth, $user);
        \expect($response->allowed())->toBeTrue();
    });

    \it('allows a user to update their own data', function () {
        $userID = UserID::make();
        $auth = new User([
            'id' => $userID,
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => $userID]);
        $policy = new UserPolicy;

        $response = $policy->update($auth, $user);
        \expect($response->allowed())->toBeTrue();
    });

    \it('denies normal user from updating another user', function () {
        $auth = new User([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => UserID::make()]);
        $policy = new UserPolicy;

        $response = $policy->update($auth, $user);
        \expect($response->denied())->toBeTrue();
    });
});

\describe('delete', function () {
    \it('allows admin to delete any user', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->delete($auth, $user);
        \expect($response->allowed())->toBeTrue();
    });

    \it('denies normal user from deleting users', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::User)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->delete($auth, $user);
        \expect($response->denied())->toBeTrue();
    });
});
