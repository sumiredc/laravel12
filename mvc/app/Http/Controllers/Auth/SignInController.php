<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\UseCases\Auth\SignInUseCase;
use Illuminate\Http\JsonResponse;

final class SignInController extends Controller
{
    public function __invoke(SignInRequest $request): JsonResponse
    {
        $useCase = app(SignInUseCase::class);
        $resource = $useCase($request);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
