<?php

declare(strict_types=1);

use App\Exceptions\InvalidCredentialException;
use App\Http\Requests\Auth\SignInRequestInterface;
use App\Models\User;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\UseCases\Auth\SignInUseCase;
use App\ValueObjects\User\UserID;

describe('SignInUseCase', function () {
    $tokenRepository = Mockery::mock(TokenRepositoryInterface::class);
    $userRepository = Mockery::mock(UserRepositoryInterface::class);
    $request = Mockery::mock(SignInRequestInterface::class);

    it('Success login user', function () use ($request, $userRepository, $tokenRepository) {
        $useCase = new SignInUseCase($userRepository, $tokenRepository);
        $userID = UserID::make();
        $user = (new User)->setRawAttributes([
            'id' => $userID->value,
            'password' => '$2y$12$wTQzXbEi7Cn.LjOc2iE9yu5gzpCnYzCQUpxrWHOPakwsB03cLjLN.', // password
        ]);
        $token = 'ZPpXtv4FYA2XZk7dFlQZZLStyXmc8uKu8SCRiKlxO2Ckl1Y9TxIMaOzdUD9heJW6';

        $request->shouldReceive('loginID')->andReturn('login-id');
        $request->shouldReceive('password')->andReturn('password');
        $request->shouldReceive('newPassword')->andReturn('newPassword');
        $userRepository->shouldReceive('getByEmail')->once()->andReturn($user);
        $tokenRepository->shouldReceive('createUserAuthorizationToken')->once()->andReturn($token);

        DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());

        $result = $useCase($request);
        expect((string) $result->resource['user']->id)->toBe((string) $userID);
        expect($result->resource['token'])->toBe($token);
    });

    it('Fail not found user', function () use ($request, $userRepository, $tokenRepository) {
        $useCase = new SignInUseCase($userRepository, $tokenRepository);
        $userRepository->shouldReceive('getByEmail')->once()->andReturnNull();
        $request->shouldReceive('loginID')->andReturn('login-id');

        $useCase($request);
    })
        ->throws(InvalidCredentialException::class);

    it('Fail invalid credentials', function () use ($request, $userRepository, $tokenRepository) {
        $useCase = new SignInUseCase($userRepository, $tokenRepository);
        $userID = UserID::make();
        $user = (new User)->setRawAttributes([
            'id' => $userID->value,
            'password' => '$2y$12$0i5sJaN1UmU1ZoyyhRPLdetoDMPNc8j9ynK1YQPSVIzHfHKcBFyBK', // not-password
        ]);

        $userRepository->shouldReceive('getByEmail')->once()->andReturn($user);
        $request->shouldReceive('loginID')->andReturn('login-id');
        $request->shouldReceive('password')->andReturn('password');

        $useCase($request);
    })
        ->throws(InvalidCredentialException::class);
});
