<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstSignInRequest;
use App\Http\Resources\Auth\SignInUserResource;
use App\Http\Responses\ErrorResponse;
use App\UseCases\Auth\FirstSignInInput;
use App\UseCases\Auth\FirstSignInUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class FirstSignInController extends Controller
{
    public function __invoke(FirstSignInRequest $request): JsonResponse
    {
        $input = new FirstSignInInput($request->loginID(), $request->password(), $request->newPassword());
        $useCase = app(FirstSignInUseCase::class);

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
