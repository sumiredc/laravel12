<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\MailRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\MailSendFailedException;
use App\UseCases\User\UserCreateInput;
use App\UseCases\User\UserCreateOutput;
use App\UseCases\User\UserCreateUseCase;
use Illuminate\Support\Facades\DB;

\beforeEach(function () {
    $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
    $this->mailRepository = Mockery::mock(MailRepositoryInterface::class);
    $this->input = new UserCreateInput('sample name', 'email@xxx.xx');
});

\describe('__invoke', function () {
    \it('returns ok result', function () {
        $user = new User(UserID::make(), RoleID::User, $this->input->name, $this->input->email);
        $this->userRepository->shouldReceive('existsByEmail')->andReturn(Result::ok(false));
        $this->userRepository->shouldReceive('create')->andReturn(Result::ok($user));
        $this->mailRepository->shouldReceive('sendInitialPassword')->andReturn(Result::ok(null));

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $useCase = new UserCreateUseCase($this->userRepository, $this->mailRepository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(UserCreateOutput::class);
        \expect($result->getValue()->user->name)->toBe($user->name);
        \expect($result->getValue()->user->email)->toBe($user->email);
        \expect($result->getValue()->user->roleID)->toBe(RoleID::User);
    });

    \it('returns err result when existsByEmail method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->userRepository->shouldReceive('existsByEmail')->andReturn($err);

        $useCase = new UserCreateUseCase($this->userRepository, $this->mailRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns err result when existsByEmail method returns true', function () {
        $ok = Result::Ok(true);
        $this->userRepository->shouldReceive('existsByEmail')->andReturn($ok);

        $useCase = new UserCreateUseCase($this->userRepository, $this->mailRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(EmailAlreadyTakenException::class);
    });

    \it('returns err result when create method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->userRepository->shouldReceive('existsByEmail')->andReturn(Result::ok(false));
        $this->userRepository->shouldReceive('create')->andReturn($err);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $useCase = new UserCreateUseCase($this->userRepository, $this->mailRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });

    \it('returns err result when sendInitialPassword method returns error', function () {
        $user = new User(UserID::make(), RoleID::User, $this->input->name, $this->input->email);
        $this->userRepository->shouldReceive('existsByEmail')->andReturn(Result::ok(false));
        $this->userRepository->shouldReceive('create')->andReturn(Result::ok($user));
        $err = Result::err(new MailSendFailedException);
        $this->mailRepository->shouldReceive('sendInitialPassword')->andReturn($err);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $useCase = new UserCreateUseCase($this->userRepository, $this->mailRepository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(MailSendFailedException::class);
    });
});
