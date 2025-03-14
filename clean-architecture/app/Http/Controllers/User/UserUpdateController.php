<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\ValueObjects\UserID;
use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\ErrorResponse;
use App\UseCases\User\UserUpdateInput;
use App\UseCases\User\UserUpdateUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class UserUpdateController extends Controller
{
    public function __invoke(UserUpdateRequest $request, string $userID): JsonResponse
    {
        $result = UserID::parse($userID);
        if ($result->isErr()) {
            new ErrorResponse(JsonResponse::HTTP_NOT_FOUND);
        }

        $input = new UserUpdateInput($result->getValue(), $request->name(), $request->email());

        $useCase = app(UserUpdateUseCase::class);
        $result = $useCase($input);

        if ($result->isErr()) {
            $err = $result->getError();
            if ($err instanceof NotFoundException) {
                return new ErrorResponse(JsonResponse::HTTP_NOT_FOUND);
            } elseif ($err instanceof EmailAlreadyTakenException) {
                return new JsonResponse(['message' => $err->getMessage()], $err->getCode());
            }

            Log::error('failed to update user', ['err' => $err]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $output = $result->getValue();
        $resource = new UserResource($output->user);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
