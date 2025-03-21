<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Http\Requests\Auth\SignOutRequestInterface;
use App\Repositories\TokenRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class SignOutUseCase
{
    public function __construct(
        private TokenRepositoryInterface $tokenRepository
    ) {}

    public function __invoke(SignOutRequestInterface $request): void
    {
        DB::transaction(function () use ($request) {
            $user = $request->userOrFail();
            $this->tokenRepository->revokeUserAuthorizationToken($user);
        });
    }
}
