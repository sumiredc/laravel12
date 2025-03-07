<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

final class UserCreateUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(
        UserCreateRequest $request
    ): UserResource {
        return DB::transaction(function () use ($request) {
            $user = $this->userRepository
                ->create(
                    $request->name(),
                    $request->email(),
                );

            return new UserResource($user);
        });
    }
}
