<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserDeleteRequest;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UserDeleteUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(UserDeleteRequest $request, User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->userRepository->delete($user);
        });
    }
}
