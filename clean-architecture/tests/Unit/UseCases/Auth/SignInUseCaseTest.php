<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\HashedPassword;
use App\Domain\ValueObjects\OAuthToken;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialException;
use App\Exceptions\NotFoundException;
use App\UseCases\Auth\SignInInput;
use App\UseCases\Auth\SignInOutput;
use App\UseCases\Auth\SignInUseCase;
use Illuminate\Support\Facades\DB;

\beforeEach(function () {
    $this->input = new SignInInput('xxx@xxx.xx', 'password');
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
    $this->tokenRepository = Mockery::mock(TokenRepositoryInterface::class);
});

\describe('__invoke', function () {
    \it('returns ok result', function () {
        $user = new User(UserID::make(), RoleID::User, 'sample', $this->input->loginID);
        $token = new OAuthToken('token-value');
        $password = HashedPassword::parse('$2y$12$YkS4FPdwus3ynRLr0u76eulHLUdvh6zlCpzot34RIVVUByyh0G3nW')->getValue();
        $okUser = Result::ok([$user, $password]);
        $okToken = Result::ok($token);
        $this->userRepository->shouldReceive('getByEmailWithPassword')->andReturn($okUser);
        $this->tokenRepository->shouldReceive('createUserAuthorizationToken')->andReturn($okToken);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $useCase = new SignInUseCase($this->userRepository, $this->tokenRepository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(SignInOutput::class);
        \expect($result->getValue()->user)->toBe($user);
        \expect($result->getValue()->token)->toBe($token);
    });

    \it('returns err result when getByEmailWithPassword method returns NotFoundException', function () {
        $err = Result::err(new NotFoundException);
        $this->userRepository->shouldReceive('getByEmailWithPassword')->andReturn($err);

        $useCase = new SignInUseCase($this->userRepository, $this->tokenRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InvalidCredentialException::class);
    });

    \it('returns err result when getByEmailWithPassword method returns InternalServerErrorException', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->userRepository->shouldReceive('getByEmailWithPassword')->andReturn($err);

        $useCase = new SignInUseCase($this->userRepository, $this->tokenRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns err result when password dont match', function () {
        $user = new User(UserID::make(), RoleID::User, 'sample', $this->input->loginID);
        $password = HashedPassword::parse('$2y$12$CGn6aYoQz0mUXDvCTWqvjeSVMLD.oDwtUxoLLTKQI6btn95bhL8qe')->getValue();
        $ok = Result::ok([$user, $password]);
        $this->userRepository->shouldReceive('getByEmailWithPassword')->andReturn($ok);
        DB::shouldReceive('beginTransaction')->never();

        $useCase = new SignInUseCase($this->userRepository, $this->tokenRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InvalidCredentialException::class);
    });

    \it('returns err result when createUserAuthorizationToken method returns error', function () {
        $user = new User(UserID::make(), RoleID::User, 'sample', $this->input->loginID);
        $password = HashedPassword::parse('$2y$12$YkS4FPdwus3ynRLr0u76eulHLUdvh6zlCpzot34RIVVUByyh0G3nW')->getValue();
        $ok = Result::ok([$user, $password]);
        $err = Result::err(new InternalServerErrorException);
        $this->userRepository->shouldReceive('getByEmailWithPassword')->andReturn($ok);
        $this->tokenRepository->shouldReceive('createUserAuthorizationToken')->andReturn($err);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $useCase = new SignInUseCase($this->userRepository, $this->tokenRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });
});
