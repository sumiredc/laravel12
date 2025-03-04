<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserGetRequest;
use App\Models\User;
use App\UseCases\User\UserCreateUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

final class UserGetController extends Controller
{
    public function __invoke(UserGetRequest $request, User $user): JsonResponse
    {
        $useCase = app(UserCreateUseCase::class);

        $resource = $useCase($request, $user);

        return Response::json($resource, JsonResponse::HTTP_OK);
    }
}
