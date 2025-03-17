<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;
use App\Http\Requests\User\UserListRequest;
use App\Models\OAuthAccessToken;
use App\Models\User;
use App\Rules\Common\PositiveNaturalNumberRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

\beforeEach(function () {
    $dummyRule = function () {};
    \app()->bind(PositiveNaturalNumberRule::class, $dummyRule);
});

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

        $request = new UserListRequest;
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

        $request = new UserListRequest;
        $request->setUserResolver(fn () => $auth);

        $result = $request->authorize();

        \expect($result)->not->toBeTrue();
    });

    \it('denies authorization when guest user accesses', function () {
        $request = new UserListRequest;

        $result = $request->authorize();

        \expect($result)->not->toBeTrue();
    });
});

\describe('rules', function () {
    \it('passes validation with valid data', function () {
        $data = [
            'offset' => 1,
            'limit' => 100,
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $request = new UserListRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->passes())->toBeTrue();
    });

    \it('fails validation with invalid values', function () {
        $invalidData = [
            'offset' => 'aaa',
            'limit' => 101,
            'name' => \str_repeat('a', 51),
            'email' => \str_repeat('a', 51),
        ];

        $request = new UserListRequest;
        $rules = $request->rules();

        $validator = Validator::make($invalidData, $rules);

        \expect($validator->fails())->toBeTrue();
        \expect($validator->errors()->has('offset'))->toBeTrue();
        \expect($validator->errors()->has('limit'))->toBeTrue();
        \expect($validator->errors()->has('name'))->toBeTrue();
        \expect($validator->errors()->has('email'))->toBeTrue();

    });
});

\describe('accessor offset, limit, name, email', function () {
    \it('retrieves validated input values correctly', function () {
        $data = [
            'offset' => 1,
            'limit' => 100,
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $mock = Mockery::mock(ValidationValidator::class);
        $mock->shouldReceive('validated')->andReturn($data);
        $request = UserListRequest::create('/', 'GET', $data);
        $request->setValidator($mock);

        \expect($request->offset())->toBe(1);
        \expect($request->limit())->toBe(100);
        \expect($request->name())->toBe('validName');
        \expect($request->email())->toBe('validEmail');
    });
});
