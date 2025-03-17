<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;
use App\Http\Requests\User\UserDeleteRequest;
use App\Models\OAuthAccessToken;
use App\Models\User;

\describe('authorize', function () {
    \it('allows authorization when Admin role user authorizes', function () {
        $auth = new User;
        $auth->id = UserID::make()->value;
        $auth->role_id = RoleID::Admin->value;
        $auth->name = 'sample name';
        $auth->email = 'email@xxx.xx';

        $tokenID = OAuthTokenID::parse('token-id');
        $clientID = OAuthClientID::parse('4d3c3792-10a5-4996-8e9d-1a7068d78860')->getValue();
        $token = new OAuthAccessToken;
        $token->id = $tokenID->value;
        $token->client_id = $clientID->value;

        $auth->withAccessToken($token);

        $request = new UserDeleteRequest;
        $request->setUserResolver(fn () => $auth);

        $result = $request->authorize();

        \expect($result)->toBeTrue();
    });

    \it('denies authorization when User role user authorizes', function () {
        $auth = new User;
        $auth->id = UserID::make()->value;
        $auth->role_id = RoleID::User->value;
        $auth->name = 'sample name';
        $auth->email = 'email@xxx.xx';

        $tokenID = OAuthTokenID::parse('token-id');
        $clientID = OAuthClientID::parse('4d3c3792-10a5-4996-8e9d-1a7068d78860')->getValue();
        $token = new OAuthAccessToken;
        $token->id = $tokenID->value;
        $token->client_id = $clientID->value;

        $auth->withAccessToken($token);

        $request = new UserDeleteRequest;
        $request->setUserResolver(fn () => $auth);

        $result = $request->authorize();

        \expect($result)->not->toBeTrue();
    });

    \it('denies authorization when guest user accesses', function () {
        $request = new UserDeleteRequest;

        $result = $request->authorize();

        \expect($result)->not->toBeTrue();
    });
});
