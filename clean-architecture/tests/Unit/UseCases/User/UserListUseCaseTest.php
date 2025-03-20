<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\UseCases\User\UserListInput;
use App\UseCases\User\UserListOutput;
use App\UseCases\User\UserListUseCase;

\beforeEach(function () {
    $mock = Mockery::mock(UserRepositoryInterface::class);
    $this->repository = $mock;
    $this->input = new UserListInput(0, 100, 'new name', 'new@xxx.xx');
});

\describe('__invoke', function () {
    \it('returns Ok result', function () {
        $users = \array_map(function ($i) {
            return new User(UserID::make(), RoleID::User, "name{$i}", "email{$i}@xxx.xx");
        }, \range(0, 10));
        $this->repository->shouldReceive('list')->once()->andReturn(Result::ok($users));

        $useCase = new UserListUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(UserListOutput::class);
        \expect($result->getValue()->users)->toBe($users);
    });

    \it('returns Err result when list method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->repository->shouldReceive('list')->once()->andReturn($err);

        $useCase = new UserListUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });
});
