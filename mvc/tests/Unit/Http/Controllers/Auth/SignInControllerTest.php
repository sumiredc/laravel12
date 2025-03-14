<?php

declare(strict_types=1);

use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\UseCases\Auth\SignInUseCase;
use Illuminate\Support\Facades\Log;

describe('SignInController', function () {
    beforeEach(function () {
        $this->request = new SignInRequest;
    });

    it('response 200', function () {
        $resource = new UserResource(new User);

        $mock = Mockery::mock(new class
        {
            public function __invoke() {}
        });
        $mock->shouldReceive('__invoke')->once()->andReturn($resource);
        app()->bind(SignInUseCase::class, fn () => $mock);

        $controller = new SignInController;
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
        app()->bind(SignInUseCase::class, fn () => $mock);

        $controller = new SignInController;
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
        app()->bind(SignInUseCase::class, fn () => $mock);

        $controller = new SignInController;
        $controller($this->request);
    })
        ->throws(InvalidCredentialException::class);

});
