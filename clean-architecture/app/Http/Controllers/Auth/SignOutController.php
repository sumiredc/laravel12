<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignOutRequest;
use App\Http\Responses\ErrorResponse;
use App\UseCases\Auth\SignOutInput;
use App\UseCases\Auth\SignOutUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class SignOutController extends Controller
{
    public function __invoke(SignOutRequest $request): JsonResponse
    {
        $result = $request->authUser();
        if ($result->isErr()) {
            Log::error('failed user sign-out, unauthorized', ['error' => $result->getError()]);

            return new ErrorResponse($result->getError()->getCode());
        }

        $input = new SignOutInput($result->getValue());
        $useCase = app(SignOutUseCase::class);
        $result = $useCase($input);

        if ($result->isErr()) {
            Log::error('failed user sign-out', ['error' => $result->getError()]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
