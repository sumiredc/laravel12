<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Exceptions\EmailAlreadyTakenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\ErrorResponse;
use App\UseCases\User\UserCreateInput;
use App\UseCases\User\UserCreateUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class UserCreateController extends Controller
{
    public function __invoke(UserCreateRequest $request): JsonResponse
    {
        $input = new UserCreateInput($request->name(), $request->email());
        $useCase = app(UserCreateUseCase::class);
        $result = $useCase($input);

        if ($result->isErr()) {
            $err = $result->getError();
            if ($err instanceof EmailAlreadyTakenException) {
                return new JsonResponse(['message' => $err->getMessage()], $err->getCode());
            }

            Log::error('failed to create user', ['err' => $err]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $output = $result->getValue();
        $resource = new UserResource($output->user);

        return new JsonResponse($resource, JsonResponse::HTTP_CREATED);
    }
}
