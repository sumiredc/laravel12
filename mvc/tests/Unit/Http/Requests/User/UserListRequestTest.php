<?php

declare(strict_types=1);

use App\Exceptions\ForbiddenException;
use App\Http\Requests\User\UserListRequest;
use App\Rules\Common\PositiveNaturalNumberRule;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

describe('UserListRequestTest', function () {
    beforeEach(function () {
        $dummyRule = function () {};
        app()->bind(PositiveNaturalNumberRule::class, $dummyRule);
    });

    it('passes authorize', function () {
        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(true));
        $request = new UserListRequest;
        $result = $request->authorize();

        expect($result)->toBeTrue();
    });

    it('denied authorize', function () {

        Gate::partialMock()->shouldReceive('inspect')->andReturn(new Response(false));
        $request = new UserListRequest;
        $request->authorize();

    })
        ->throws(ForbiddenException::class);

    it('validates successfully', function () {
        $data = [
            'offset' => 1,
            'limit' => 100,
            'name' => 'validName',
            'email' => 'validEmail',
        ];

        $request = new UserListRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        expect($validator->passes())->toBeTrue();
    });

    it('fails validation when fields are missing', function () {
        $data = [
            'offset' => 'aaa',
            'limit' => 101,
            'name' => str_repeat('a', 51),
            'email' => str_repeat('a', 51),
        ];

        $request = new UserListRequest;
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has('offset'))->toBeTrue();
        expect($validator->errors()->has('limit'))->toBeTrue();
        expect($validator->errors()->has('name'))->toBeTrue();
        expect($validator->errors()->has('email'))->toBeTrue();

    });

    it('retrieves validated values', function () {
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

        expect($request->offset())->toBe(1);
        expect($request->limit())->toBe(100);
        expect($request->name())->toBe('validName');
        expect($request->email())->toBe('validEmail');
    });
});
