<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Models\User;
use App\UseCases\User\UserDeleteUseCase;
use Illuminate\Http\JsonResponse;

final class UserDeleteController extends Controller
{
    public function __invoke(UserDeleteRequest $request, User $user): JsonResponse
    {
        $useCase = app(UserDeleteUseCase::class);
        $useCase($request, $user);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
