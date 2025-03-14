<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\ValueObjects\UserID;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Responses\ErrorResponse;
use App\UseCases\User\UserDeleteInput;
use App\UseCases\User\UserDeleteUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class UserDeleteController extends Controller
{
    public function __invoke(UserDeleteRequest $request, string $userID): JsonResponse
    {
        $result = UserID::parse($userID);
        if ($result->isErr()) {
            new ErrorResponse(JsonResponse::HTTP_NOT_FOUND);
        }

        $input = new UserDeleteInput($result->getValue());

        $useCase = app(UserDeleteUseCase::class);
        $result = $useCase($input);

        if ($result->isErr()) {
            $err = $result->getError();
            if ($err instanceof NotFoundException) {
                return new ErrorResponse(JsonResponse::HTTP_NOT_FOUND);
            }

            Log::error('failed to delete user', ['err' => $err]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
