<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\UseCases\User\UserCreateUseCase;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UserCreateController extends Controller
{
    public function __invoke(UserCreateRequest $request): JsonResponse
    {
        $useCase = app(UserCreateUseCase::class);

        try {
            $resource = $useCase($request);

            return new JsonResponse($resource, JsonResponse::HTTP_CREATED);
        } catch (HttpResponseException $resEx) {
            throw $resEx;
        } catch (Throwable $e) {
            Log::error('failed to create user', ['error' => $e]);

            throw new InternalServerErrorException;
        }
    }
}
