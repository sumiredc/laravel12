<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserListRequest;
use App\UseCases\User\UserListUseCase;
use Illuminate\Http\JsonResponse;

final class UserListController extends Controller
{
    public function __invoke(UserListRequest $request): JsonResponse
    {
        $useCase = app(UserListUseCase::class);
        $resource = $useCase($request);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
