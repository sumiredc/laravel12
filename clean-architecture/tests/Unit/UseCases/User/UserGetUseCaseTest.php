<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;
use App\UseCases\User\UserGetInput;
use App\UseCases\User\UserGetOutput;
use App\UseCases\User\UserGetUseCase;

\beforeEach(function () {
    $mock = Mockery::mock(UserRepositoryInterface::class);
    $this->repository = $mock;
    $this->userID = UserID::make();
    $this->input = new UserGetInput($this->userID);
});

\describe('__invoke', function () {
    \it('returns Ok result', function () {
        $user = new User($this->userID, RoleID::User, 'name', 'email@xxx.xx');
        $this->repository->shouldReceive('get')->once()->andReturn(Result::ok($user));

        $useCase = new UserGetUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(UserGetOutput::class);
        \expect($result->getValue()->user)->toBe($user);
    });

    \it('returns Err result when get method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->repository->shouldReceive('get')->once()->andReturn($err);

        $useCase = new UserGetUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns Err result when get method returns null', function () {
        $this->repository->shouldReceive('get')->once()->andReturn(Result::ok(null));

        $useCase = new UserGetUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(NotFoundException::class);
    });
});
