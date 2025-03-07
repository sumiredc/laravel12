<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserListRequest;
use App\Http\Resources\User\UserListResource;
use App\Repositories\UserRepository;

final class UserListUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(UserListRequest $request): UserListResource
    {
        $users = $this->userRepository->list(
            $request->offset(),
            $request->limit(),
            $request->name(),
            $request->email(),
        );

        return new UserListResource($users);
    }
}
