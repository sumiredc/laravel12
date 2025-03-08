<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignOutRequest;
use App\UseCases\Auth\SignOutUseCase;
use Illuminate\Http\JsonResponse;

final class SignOutController extends Controller
{
    public function __invoke(SignOutRequest $request): JsonResponse
    {
        $useCase = app(SignOutUseCase::class);
        $useCase($request);

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
