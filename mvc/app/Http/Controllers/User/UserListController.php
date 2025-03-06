<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserListRequest;
use App\UseCases\User\UserListUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UserListController extends Controller
{
    public function __invoke(UserListRequest $request): JsonResponse
    {
        try {
            $useCase = app(UserListUseCase::class);
            $resource = $useCase($request);

            return new JsonResponse($resource, JsonResponse::HTTP_OK);
        } catch (Throwable $e) {
            Log::error('failed to get users', ['error' => $e]);

            throw new InternalServerErrorException;
        }
    }
}
