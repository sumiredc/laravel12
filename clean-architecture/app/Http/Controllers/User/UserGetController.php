<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\ValueObjects\UserID;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\ErrorResponse;
use App\UseCases\User\UserGetInput;
use App\UseCases\User\UserGetUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class UserGetController extends Controller
{
    public function __invoke(UserGetRequest $request, string $userID): JsonResponse
    {
        $result = UserID::parse($userID);
        if ($result->isErr()) {
            new ErrorResponse(JsonResponse::HTTP_NOT_FOUND);
        }

        $input = new UserGetInput($result->getValue());

        $useCase = app(UserGetUseCase::class);
        $result = $useCase($input);

        if ($result->isErr()) {
            $err = $result->getError();
            if ($err instanceof NotFoundException) {
                return new ErrorResponse(JsonResponse::HTTP_NOT_FOUND);
            }

            Log::error('failed to get user', ['err' => $err]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $output = $result->getValue();
        $resource = new UserResource($output->user);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
