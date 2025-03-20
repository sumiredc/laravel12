<?php

declare(strict_types=1);

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;
use App\UseCases\User\UserDeleteInput;
use App\UseCases\User\UserDeleteOutput;
use App\UseCases\User\UserDeleteUseCase;
use Illuminate\Support\Facades\DB;

\beforeEach(function () {
    $mock = Mockery::mock(UserRepositoryInterface::class);
    $this->repository = $mock;
    $this->userID = UserID::make();
    $this->input = new UserDeleteInput($this->userID);
});

\describe('__invoke', function () {
    \it('returns Ok result', function () {
        $this->repository->shouldReceive('delete')->once()->andReturn(Result::ok(1));
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $useCase = new UserDeleteUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(UserDeleteOutput::class);
    });

    \it('returns Err result when delete method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->repository->shouldReceive('delete')->once()->andReturn($err);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $useCase = new UserDeleteUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns Err result when delete method returns 0', function () {
        $this->repository->shouldReceive('delete')->once()->andReturn(Result::ok(0));
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $useCase = new UserDeleteUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(NotFoundException::class);
    });
});
