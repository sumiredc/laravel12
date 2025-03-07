<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\UseCases\User\UserCreateUseCase;
use Illuminate\Http\JsonResponse;

final class UserCreateController extends Controller
{
    public function __invoke(UserCreateRequest $request): JsonResponse
    {
        $useCase = app(UserCreateUseCase::class);
        $resource = $useCase($request);

        return new JsonResponse($resource, JsonResponse::HTTP_CREATED);
    }
}
