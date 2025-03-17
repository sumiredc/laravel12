<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;
use App\Http\Requests\User\UserCreateRequest;
use App\Models\OAuthAccessToken;
use App\Models\User;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

\beforeEach(function () {
    $dummyRule = function () {};
    \app()->bind(UserNameRule::class, $dummyRule);
    \app()->bind(UserEmailRule::class, $dummyRule);
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

        $request = new UserCreateRequest;
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

        $request = new UserCreateRequest;
        $request->setUserResolver(fn () => $auth);

        $result = $request->authorize();

        \expect($result)->not->toBeTrue();
    });

    \it('denies authorization when guest user accesses', function () {
        $request = new UserCreateRequest;

        $result = $request->authorize();

        \expect($result)->not->toBeTrue();
    });
});

\describe('rules', function () {
    \it('passes validation with valid data', function () {
        $data = [
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $request = new UserCreateRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->passes())->toBeTrue();
    });

    \it('fails validation when required fields are missing', function () {
        $invalidData = [];

        $request = new UserCreateRequest;
        $rules = $request->rules();

        $validator = Validator::make($invalidData, $rules);

        \expect($validator->fails())->toBeTrue();
        \expect($validator->errors()->has('name'))->toBeTrue();
        \expect($validator->errors()->has('email'))->toBeTrue();

    });
});

\describe('accessor name, email', function () {
    \it('retrieves validated input values correctly', function () {
        $data = [
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $mock = Mockery::mock(ValidationValidator::class);
        $mock->shouldReceive('validated')->andReturn($data);
        $request = UserCreateRequest::create('/', 'POST', $data);
        $request->setValidator($mock);

        \expect($request->name())->toBe('validName');
        \expect($request->email())->toBe('validEmail');
    });
});
