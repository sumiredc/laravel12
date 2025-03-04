<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Http\Requests\User\UserGetRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;

final class UserGetUseCase
{
    public function __construct() {}

    public function __invoke(UserGetRequest $request, User $user): UserResource
    {
        return new UserResource($user);
    }
}
