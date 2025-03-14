<?php

declare(strict_types=1);

use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Auth\FirstSignInController;
use App\Http\Requests\Auth\FirstSignInRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\UseCases\Auth\FirstSignInUseCase;
use Illuminate\Support\Facades\Log;

describe('FirstSignInController', function () {
    beforeEach(function () {
        $this->request = new FirstSignInRequest;
    });

    it('response 200', function () {
        $resource = new UserResource(new User);

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once()->andReturn($resource);
        app()->bind(FirstSignInUseCase::class, fn () => $mock);

        $controller = new FirstSignInController;
        $result = $controller($this->request);

        expect($result->getStatusCode())->toBe(200);
    });

    it('throws InvalidCredentialException', function () {
        Log::shouldReceive('error')->never();

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once()->andThrow(InvalidCredentialException::class);
        app()->bind(FirstSignInUseCase::class, fn () => $mock);

        $controller = new FirstSignInController;
        $controller($this->request);
    })
        ->throws(InvalidCredentialException::class);

    it('throws InvalidCredentialException and call report', function () {
        Log::shouldReceive('error')->once();

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once()->andThrow(Exception::class);
        app()->bind(FirstSignInUseCase::class, fn () => $mock);

        $controller = new FirstSignInController;
        $controller($this->request);
    })
        ->throws(InvalidCredentialException::class);

});
