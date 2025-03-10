<?php

declare(strict_types=1);

use App\Exceptions\InvalidCredentialException;
use App\Http\Requests\Auth\FirstSignInRequestInterface;
use App\Models\User;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\UseCases\Auth\FirstSignInUseCase;
use App\ValueObjects\User\UserID;
use Illuminate\Support\Facades\DB;

describe('FirstSignInUseCase', function () {
    beforeEach(function () {
        $this->tokenRepository = Mockery::mock(TokenRepositoryInterface::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->request = Mockery::mock(FirstSignInRequestInterface::class);
    });

    it('Success first login user', function () {
        $useCase = new FirstSignInUseCase($this->userRepository, $this->tokenRepository);
        $userID = UserID::make();
        $user = (new User)->setRawAttributes([
            'id' => $userID->value,
            'password' => '$2y$12$wTQzXbEi7Cn.LjOc2iE9yu5gzpCnYzCQUpxrWHOPakwsB03cLjLN.', // password
        ]);
        $token = 'ZPpXtv4FYA2XZk7dFlQZZLStyXmc8uKu8SCRiKlxO2Ckl1Y9TxIMaOzdUD9heJW6';

        $this->request->shouldReceive('loginID')->andReturn('login-id');
        $this->request->shouldReceive('password')->andReturn('password');
        $this->request->shouldReceive('newPassword')->andReturn('newPassword');
        $this->userRepository->shouldReceive('getUnverifiedByEmail')->once()->andReturn($user);
        $this->userRepository->shouldReceive('update')->once()->andReturn($user);
        $this->userRepository->shouldReceive('verifyEmail')->once();
        $this->tokenRepository->shouldReceive('createUserAuthorizationToken')->once()->andReturn($token);

        DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());

        $result = $useCase($this->request);
        expect((string) $result->resource['user']->id)->toBe((string) $userID);
        expect($result->resource['token'])->toBe($token);
    });

    it('Fail not found user', function () {
        $useCase = new FirstSignInUseCase($this->userRepository, $this->tokenRepository);
        $this->userRepository->shouldReceive('getUnverifiedByEmail')->once()->andReturnNull();
        $this->request->shouldReceive('loginID')->andReturn('login-id');

        $useCase($this->request);
    })
        ->throws(InvalidCredentialException::class);

    it('Fail invalid credentials', function () {
        $useCase = new FirstSignInUseCase($this->userRepository, $this->tokenRepository);
        $userID = UserID::make();
        $user = (new User)->setRawAttributes([
            'id' => $userID->value,
            'password' => '$2y$12$0i5sJaN1UmU1ZoyyhRPLdetoDMPNc8j9ynK1YQPSVIzHfHKcBFyBK', // not-password
        ]);

        $this->userRepository->shouldReceive('getUnverifiedByEmail')->once()->andReturn($user);
        $this->request->shouldReceive('loginID')->andReturn('login-id');
        $this->request->shouldReceive('password')->andReturn('password');

        $useCase($this->request);
    })
        ->throws(InvalidCredentialException::class);
});
