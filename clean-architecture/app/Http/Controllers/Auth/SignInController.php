<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Resources\Auth\SignInUserResource;
use App\Http\Responses\ErrorResponse;
use App\UseCases\Auth\SignInInput;
use App\UseCases\Auth\SignInUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class SignInController extends Controller
{
    public function __invoke(SignInRequest $request): JsonResponse
    {
        $input = new SignInInput($request->loginID(), $request->password());
        $useCase = app(SignInUseCase::class);

        $result = $useCase($input);

        if ($result->isErr()) {
            $err = $result->getError();
            if ($err instanceof InvalidCredentialException) {
                return new JsonResponse(['message' => $err->getMessage()], $err->getCode());
            }

            Log::error('failed sign in', ['error' => $result->getError()]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $output = $result->getValue();
        $resource = new SignInUserResource($output->user, $output->token);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
