<?php

declare(strict_types=1);

use App\Domain\Consts\RoleID;
use App\Domain\Entities\AuthUser;
use App\Domain\Entities\OAuthPersonalAccessToken;
use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\UseCases\Auth\SignOutInput;
use App\UseCases\Auth\SignOutOutput;
use App\UseCases\Auth\SignOutUseCase;
use Illuminate\Support\Facades\DB;

\beforeEach(function () {
    $authUser = new AuthUser(
        UserID::make(),
        RoleID::User,
        'sample name',
        'sample@xxx.xx',
        new OAuthPersonalAccessToken(OAuthTokenID::parse('token-id'),
            OAuthClientID::parse('8934681e-08eb-4f86-aa06-585a37509885')->getValue())
    );

    $this->input = new SignOutInput($authUser);
    $this->repository = Mockery::mock(TokenRepositoryInterface::class);
});

\describe('__invoke', function () {
    \it('returns ok result', function () {
        $this->repository->shouldReceive('revokeUserAuthorizationToken')->andReturn(Result::ok(null));
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $useCase = new SignOutUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()::class)->toBe(SignOutOutput::class);
    });

    \it('returns err result when revokeUserAuthorizaionToken method returns error', function () {
        $err = Result::err(new InternalServerErrorException);
        $this->repository->shouldReceive('revokeUserAuthorizationToken')->andReturn($err);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $useCase = new SignOutUseCase($this->repository);
        $result = $useCase($this->input);

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InternalServerErrorException::class);
    });
});
