<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\UseCase\User\UserCreateUseCase;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class UserCreateController extends Controller
{
    public function __invoke(UserCreateRequest $request): JsonResource
    {
        $useCase = app(UserCreateUseCase::class);

        $user = $useCase();

        return app(UserResource::class, ['user' => $user]);
    }
}
