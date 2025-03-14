<?php

declare(strict_types=1);

use App\Exceptions\ForbiddenException;
use App\Http\Requests\User\UserCreateRequest;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

describe('UserCreateRequestTest', function () {
    beforeEach(function () {
        $dummyRule = function () {};
        app()->bind(UserNameRule::class, $dummyRule);
        app()->bind(UserEmailRule::class, $dummyRule);
    });

    it('passes authorize', function () {
        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(true));
        $request = new UserCreateRequest;
        $result = $request->authorize();

        expect($result)->toBeTrue();
    });

    it('denied authorize', function () {

        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(false));
        $request = new UserCreateRequest;
        $request->authorize();

    })
        ->throws(ForbiddenException::class);

    it('validates successfully', function () {
        $data = [
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $request = new UserCreateRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        expect($validator->passes())->toBeTrue();
    });

    it('fails validation when fields are missing', function () {
        $data = [];

        $request = new UserCreateRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has('name'))->toBeTrue();
        expect($validator->errors()->has('email'))->toBeTrue();

    });

    it('retrieves validated values', function () {
        $data = [
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $mock = Mockery::mock(ValidationValidator::class);
        $mock->shouldReceive('validated')->andReturn($data);
        $request = UserCreateRequest::create('/', 'POST', $data);
        $request->setValidator($mock);

        expect($request->name())->toBe('validName');
        expect($request->email())->toBe('validEmail');
    });
});
