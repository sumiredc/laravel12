<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Http\Requests\Auth\SignOutRequest;
use App\Repositories\TokenRepository;
use Illuminate\Support\Facades\DB;

final class SignOutUseCase
{
    public function __construct(
        private TokenRepository $tokenRepository
    ) {}

    public function __invoke(SignOutRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $user = $request->userOrFail();
            $this->tokenRepository->revokeUserAuthorizationToken($user);
        });
    }
}
