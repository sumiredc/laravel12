<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;
use App\UseCases\User\UserUpdateInput;
use App\UseCases\User\UserUpdateOutput;
use App\UseCases\User\UserUpdateUseCase;
use Illuminate\Support\Facades\DB;

\beforeEach(function () {
    $mock = Mockery::mock(UserRepositoryInterface::class);
    $this->repository = $mock;
    $this->userID = UserID::make();
    $this->input = new UserUpdateInput($this->userID, 'new name', 'new@xxx.xx');
});

\describe('__invoke', function () {
    \it('returns Ok result', function () {
        $oldUser = new User($this->userID, RoleID::User, 'old name', 'old@xxx.xx');
        $newUser = new User($this->userID, RoleID::User, 'new name', 'new@xxx.xx');
        $ok = Result::ok($newUser);

        $this->repository->shouldReceive('existsByEmail')->once()->andReturn(Result::ok(false));
        $this->repository->shouldReceive('get')->once()->andReturn(Result::ok($oldUser));
        $this->repository->shouldReceive('update')->once()->andReturn($ok);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $useCase = new UserUpdateUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(UserUpdateOutput::class);
        \expect($result->getValue()->user)->toBe($newUser);
    });

    \it('returns Err result when existsByEmail method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->repository->shouldReceive('existsByEmail')->once()->andReturn($err);
        $this->repository->shouldReceive('get')->never();
        DB::shouldReceive('beginTransaction')->never();

        $useCase = new UserUpdateUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns Err result when users with the same email', function () {
        $this->repository->shouldReceive('existsByEmail')->once()->andReturn(Result::ok(true));
        $this->repository->shouldReceive('get')->never();
        DB::shouldReceive('beginTransaction')->never();

        $useCase = new UserUpdateUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(EmailAlreadyTakenException::class);
    });

    \it('returns Err result when get method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->repository->shouldReceive('existsByEmail')->once()->andReturn(Result::ok(false));
        $this->repository->shouldReceive('get')->once()->andReturn($err);
        DB::shouldReceive('beginTransaction')->never();

        $useCase = new UserUpdateUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns Err result when get method returns null', function () {
        $this->repository->shouldReceive('existsByEmail')->once()->andReturn(Result::ok(false));
        $this->repository->shouldReceive('get')->once()->andReturn(Result::ok(null));
        DB::shouldReceive('beginTransaction')->never();

        $useCase = new UserUpdateUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(NotFoundException::class);
    });

    \it('returns Err result when update method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $user = new User($this->userID, RoleID::User, 'old name', 'old@xxx.xx');
        $this->repository->shouldReceive('existsByEmail')->once()->andReturn(Result::ok(false));
        $this->repository->shouldReceive('get')->once()->andReturn(Result::ok($user));
        $this->repository->shouldReceive('update')->once()->andReturn($err);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $useCase = new UserUpdateUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });
});
