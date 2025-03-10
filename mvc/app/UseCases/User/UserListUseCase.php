<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserListRequestInterface;
use App\Http\Resources\User\UserListResource;
use App\Repositories\UserRepositoryInterface;

final class UserListUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(UserListRequestInterface $request): UserListResource
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
