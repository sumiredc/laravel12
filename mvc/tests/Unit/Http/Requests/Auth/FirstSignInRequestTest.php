<?php

declare(strict_types=1);

use App\Http\Requests\Auth\FirstSignInRequest;
use App\Rules\Auth\CredentialStringRule;
use App\Rules\Auth\PasswordRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

\beforeEach(function () {
    $dummyRule = function () {};
    \app()->bind(CredentialStringRule::class, $dummyRule);
    \app()->bind(PasswordRule::class, $dummyRule);
});

\describe('FirstSignInRequestTest', function () {

    \it('passes validation with valid data', function () {
        $data = [
            'login_id' => 'validLoginID',
            'password' => 'validPassword',
            'new_password' => 'newValidPassword',
        ];

        $request = new FirstSignInRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->passes())->toBeTrue();
    });

    \it('fails validation when required fields are missing', function () {
        $data = [];

        $request = new FirstSignInRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->fails())->toBeTrue();
        \expect($validator->errors()->has('login_id'))->toBeTrue();
        \expect($validator->errors()->has('password'))->toBeTrue();
        \expect($validator->errors()->has('new_password'))->toBeTrue();
    });

    \it('retrieves validated input values correctly', function () {
        $data = [
            'login_id' => 'validLoginID',
            'password' => 'validPassword',
            'new_password' => 'newValidPassword',
        ];

        $mock = Mockery::mock(ValidationValidator::class);
        $mock->shouldReceive('validated')->andReturn($data);
        $request = FirstSignInRequest::create('/', 'POST', $data);
        $request->setValidator($mock);

        \expect($request->loginID())->toBe('validLoginID');
        \expect($request->password())->toBe('validPassword');
        \expect($request->newPassword())->toBe('newValidPassword');
    });

});
