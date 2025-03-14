<?php

declare(strict_types=1);

use App\Http\Controllers\User\UserCreateController;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\UseCases\User\UserCreateUseCase;

describe('UserCreateController', function () {
    beforeEach(function () {
        $this->request = new UserCreateRequest;
    });

    it('response 201', function () {
        $resource = new UserResource(new User);

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once()->andReturn($resource);
        app()->bind(UserCreateUseCase::class, fn () => $mock);

        $controller = new UserCreateController;
        $result = $controller($this->request);

        expect($result->getStatusCode())->toBe(201);
    });
});
