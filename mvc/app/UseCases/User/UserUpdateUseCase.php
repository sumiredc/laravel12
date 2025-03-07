<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

final class UserUpdateUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(UserUpdateRequest $request, User $user): UserResource
    {
        return DB::transaction(function () use ($request, $user): UserResource {
            $user = $this->userRepository
                ->update($user, $request->name(), $request->email());

            return new UserResource($user);
        });
    }
}
