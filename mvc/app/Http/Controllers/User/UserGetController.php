<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserGetRequest;
use App\Models\User;
use App\UseCases\User\UserGetUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UserGetController extends Controller
{
    public function __invoke(UserGetRequest $request, User $user): JsonResponse
    {
        $useCase = app(UserGetUseCase::class);

        try {
            $resource = $useCase($request, $user);

            return new JsonResponse($resource, JsonResponse::HTTP_OK);
        } catch (Throwable $e) {
            Log::error('failed to get a user', ['error' => $e]);

            throw new InternalServerErrorException;
        }

    }
}
