<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\User;
use App\Policies\UserPolicy;
use App\ValueObjects\Role\RoleID;
use App\ValueObjects\User\UserID;

describe('UserPolicy::list()', function () {
    it('allows valid admin role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->list($auth, $user);
        expect($response->allowed())->toBeTrue();
    });

    it('rejects invalid user role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::User)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->list($auth, $user);
        expect($response->denied())->toBeTrue();
    });
});

describe('UserPolicy::create()', function () {
    it('allows valid admin role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $policy = new UserPolicy;

        $response = $policy->create($auth);
        expect($response->allowed())->toBeTrue();
    });

    it('rejects invalid user role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::User)]);
        $policy = new UserPolicy;

        $response = $policy->create($auth);
        expect($response->denied())->toBeTrue();
    });
});

describe('UserPolicy::get()', function () {
    it('allows valid admin role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->get($auth, $user);
        expect($response->allowed())->toBeTrue();
    });

    it('allows valid user role and same user', function () {
        $userID = UserID::make();
        $auth = new User([
            'id' => $userID,
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => $userID]);
        $policy = new UserPolicy;

        $response = $policy->get($auth, $user);
        expect($response->allowed())->toBeTrue();
    });

    it('rejects invalid user role', function () {
        $auth = new User([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => UserID::make()]);
        $policy = new UserPolicy;

        $response = $policy->get($auth, $user);
        expect($response->denied())->toBeTrue();
    });
});

describe('UserPolicy::update()', function () {
    it('allows valid admin role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->update($auth, $user);
        expect($response->allowed())->toBeTrue();
    });

    it('allows valid user role and same user', function () {
        $userID = UserID::make();
        $auth = new User([
            'id' => $userID,
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => $userID]);
        $policy = new UserPolicy;

        $response = $policy->update($auth, $user);
        expect($response->allowed())->toBeTrue();
    });

    it('rejects invalid user role', function () {
        $auth = new User([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::User),
        ]);
        $user = new User(['id' => UserID::make()]);
        $policy = new UserPolicy;

        $response = $policy->update($auth, $user);
        expect($response->denied())->toBeTrue();
    });
});

describe('UserPolicy::delete()', function () {
    it('allows valid admin role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::Admin)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->delete($auth, $user);
        expect($response->allowed())->toBeTrue();
    });

    it('rejects invalid user role', function () {
        $auth = new User(['role_id' => RoleID::parse(Role::User)]);
        $user = new User;
        $policy = new UserPolicy;

        $response = $policy->delete($auth, $user);
        expect($response->denied())->toBeTrue();
    });
});
