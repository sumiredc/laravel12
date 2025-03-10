<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserUpdateRequestInterface;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UserUpdateUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(UserUpdateRequestInterface $request, User $user): UserResource
    {
        return DB::transaction(function () use ($request, $user): UserResource {
            $user = $this->userRepository
                ->update($user, $request->name(), $request->email());

            return new UserResource($user);
        });
    }
}
