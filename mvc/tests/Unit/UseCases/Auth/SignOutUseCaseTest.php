<?php

declare(strict_types=1);

use App\Exceptions\NotFoundException;
use App\Http\Requests\Auth\SignOutRequestInterface;
use App\Models\User;
use App\Repositories\TokenRepositoryInterface;
use App\UseCases\Auth\SignOutUseCase;
use Illuminate\Support\Facades\DB;

describe('SignOutUseCase', function () {
    beforeEach(function () {
        $this->tokenRepository = Mockery::mock(TokenRepositoryInterface::class);
        $this->request = Mockery::mock(SignOutRequestInterface::class);
    });

    it('Success logout user, not exception', function () {
        $useCase = new SignOutUseCase($this->tokenRepository);
        $user = new User;

        $this->request->shouldReceive('userOrFail')->andReturn($user);
        $this->tokenRepository->shouldReceive('revokeUserAuthorizationToken')->once();
        DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());

        $useCase($this->request);
    });

    it('Fail to get auth user', function () {
        $useCase = new SignOutUseCase($this->tokenRepository);
        $this->request->shouldReceive('userOrFail')->once()->andThrow(NotFoundException::class);
        DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());

        $useCase($this->request);
    })
        ->throws(NotFoundException::class);
});
