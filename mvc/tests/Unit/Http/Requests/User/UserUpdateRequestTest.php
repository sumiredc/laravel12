<?php

declare(strict_types=1);

use App\Exceptions\ForbiddenException;
use App\Http\Requests\User\UserUpdateRequest;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

\beforeEach(function () {
    $dummyRule = function () {};
    \app()->bind(UserNameRule::class, $dummyRule);
    \app()->bind(UserEmailRule::class, $dummyRule);
});

\describe('authorize', function () {

    \it('allows authorization when Gate permits', function () {
        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(true));
        $request = new UserUpdateRequest;
        $result = $request->authorize();

        \expect($result)->toBeTrue();
    });

    \it('throws ForbiddenException when Gate denies', function () {

        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(false));
        $request = new UserUpdateRequest;
        $request->authorize();

    })
        ->throws(ForbiddenException::class);

});

\describe('rules', function () {

    \it('passes validation with valid data', function () {
        $data = [
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $request = new UserUpdateRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        \expect($validator->passes())->toBeTrue();
    });

    \it('fails validation when required fields are missing', function () {
        $invalidData = [];

        $request = new UserUpdateRequest;
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
        $request = UserUpdateRequest::create('/', 'POST', $data);
        $request->setValidator($mock);

        \expect($request->name())->toBe('validName');
        \expect($request->email())->toBe('validEmail');
    });
});
