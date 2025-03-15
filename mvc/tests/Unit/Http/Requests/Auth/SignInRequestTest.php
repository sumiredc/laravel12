<?php

declare(strict_types=1);

use App\Http\Requests\Auth\SignInRequest;
use App\Rules\Auth\CredentialStringRule;
use App\Rules\Auth\PasswordRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

\beforeEach(function () {
    $dummyRule = function () {};
    \app()->bind(CredentialStringRule::class, $dummyRule);
    \app()->bind(PasswordRule::class, $dummyRule);
});

\describe('SignInRequestTest', function () {

    \it('passes validation with valid data', function () {
        $data = [
            'login_id' => 'validLoginID',
            'password' => 'validPassword',
        ];

        $request = new SignInRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->passes())->toBeTrue();
    });

    \it('fails validation when required fields are missing', function () {
        $data = [];

        $request = new SignInRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->fails())->toBeTrue();
        \expect($validator->errors()->has('login_id'))->toBeTrue();
        \expect($validator->errors()->has('password'))->toBeTrue();

    });

    \it('retrieves validated input values correctly', function () {
        $data = [
            'login_id' => 'validLoginID',
            'password' => 'validPassword',
        ];

        $mock = Mockery::mock(ValidationValidator::class);
        $mock->shouldReceive('validated')->andReturn($data);
        $request = SignInRequest::create('/', 'POST', $data);
        $request->setValidator($mock);

        \expect($request->loginID())->toBe('validLoginID');
        \expect($request->password())->toBe('validPassword');
    });

});
