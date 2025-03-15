<?php

declare(strict_types=1);

use App\Consts\Role;
use App\Models\OAuthClient;
use App\Models\User;
use App\Repositories\TokenRepository;
use App\ValueObjects\Role\RoleID;
use App\ValueObjects\User\UserID;
use Database\Seeders\RoleSeeder;

\beforeEach(function () {
    OAuthClient::factory()->create([
        'id' => '9e624fc3-630c-46ee-a2b5-f05ddc7bdea5',
        'secret' => 'BiwS1EkLnbyAYb802pFupRKNeCtroHFXMQvbJnJg',
        'personal_access_client' => true,
    ]);

    $this->seed(RoleSeeder::class);
    $this->repository = new TokenRepository;
});

\describe('createUserAuthorizationToken', function () {
    \it('returns an access token', function () {
        $user = User::factory()->create([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::Admin),
        ]);

        $result = $this->repository->createUserAuthorizationToken($user);

        \expect($result)->not->toBeEmpty();
        \expect($user->tokens()->count())->toBe(1);
    });
});

\describe('revokeUserAuthorizationToken', function () {
    \it('revokes the access token', function () {
        $user = User::factory()->create([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::Admin),
        ]);

        $personalAccessTokenResult = $user->createToken('');
        $user->withAccessToken($personalAccessTokenResult->token);

        $this->repository->revokeUserAuthorizationToken($user);
        $result = $user->tokens()->whereRevoked(1)->count();

        \expect($result)->toBe(1);
    });
});

\describe('deleteUserAuthorizationToken', function () {
    \it('deletes the access token', function () {
        $user = User::factory()->create([
            'id' => UserID::make(),
            'role_id' => RoleID::parse(Role::Admin),
        ]);

        $personalAccessTokenResult = $user->createToken('');
        $user->withAccessToken($personalAccessTokenResult->token);

        $this->repository->deleteUserAuthorizationToken($user);
        $result = $user->tokens()->count();

        \expect($result)->toBe(0);
    });
});
