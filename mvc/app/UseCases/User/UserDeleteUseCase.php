<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserDeleteRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

final class UserDeleteUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(UserDeleteRequest $request, User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->userRepository->delete($user);
        });
    }
}
