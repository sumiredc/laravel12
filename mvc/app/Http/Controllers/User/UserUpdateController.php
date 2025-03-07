<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\UseCases\User\UserUpdateUseCase;
use Illuminate\Http\JsonResponse;

final class UserUpdateController extends Controller
{
    public function __invoke(UserUpdateRequest $request, User $user): JsonResponse
    {
        $useCase = app(UserUpdateUseCase::class);
        $resource = $useCase($request, $user);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
