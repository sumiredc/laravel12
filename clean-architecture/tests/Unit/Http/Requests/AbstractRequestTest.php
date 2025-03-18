<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\AbstractRequest;
use App\Models\OAuthAccessToken;
use App\Models\User;

\beforeEach(function () {
    $this->request = new class extends AbstractRequest {};
    $this->auth = new User;
    $this->auth->id = UserID::make()->value;
    $this->auth->role_id = RoleID::Admin->value;
    $this->auth->name = 'sample name';
    $this->auth->email = 'email@xxx.xx';

    $token = new OAuthAccessToken;
    $token->id = 'token-id';
    $token->client_id = '4d3c3792-10a5-4996-8e9d-1a7068d78860';

    $this->auth->withAccessToken($token);
});

\describe('authUser', function () {
    \it('returns an Ok result', function () {
        $this->request->setUserResolver(fn () => $this->auth);

        $result = $this->request->authUser();

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect((string) $result->getValue()->userID)->toBe($this->auth->id);
    });

    \it('returns an Err result', function () {
        $result = $this->request->authUser();

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(UnauthorizedException::class);
    });
});

\describe('authUserOrNull', function () {
    \it('returns a User', function () {
        $this->request->setUserResolver(fn () => $this->auth);

        $result = $this->request->authUserOrNull();

        \expect((string) $result->userID)->toBe($this->auth->id);
    });

    \it('returns an Err result', function () {
        $result = $this->request->authUserOrNull();

        \expect($result)->toBeNull();
    });
});

\describe('userModelToDomain', function () {
    \it('throws InvalidArgumentException when UserID fails to parse', function () {
        $auth = new User;
        $auth->id = 'cant-parse-user-id';

        $this->request->setUserResolver(fn () => $auth);

        $this->request->authUser();
    })
        ->throws(InvalidArgumentException::class);

    \it('throws InvalidArgumentException when OAuthClientID fails to parse', function () {
        $auth = new User;
        $auth->id = UserID::make()->value;
        $auth->role_id = RoleID::Admin->value;
        $token = new OAuthAccessToken;
        $token->id = 'token-id';
        $token->client_id = 'cant-parse-client-id';
        $auth->withAccessToken($token);

        $this->request->setUserResolver(fn () => $auth);

        $this->request->authUserOrNull();
    })
        ->throws(InvalidArgumentException::class);
});
