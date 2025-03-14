<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserListRequest;
use App\Http\Resources\User\UserListResource;
use App\Http\Responses\ErrorResponse;
use App\UseCases\User\UserListInput;
use App\UseCases\User\UserListUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class UserListController extends Controller
{
    public function __invoke(UserListRequest $request): JsonResponse
    {
        $input = new UserListInput($request->offset(), $request->limit(), $request->name(), $request->email());
        $useCase = app(UserListUseCase::class);
        $result = $useCase($input);

        if ($result->isErr()) {
            $err = $result->getError();
            Log::error('failed to get users', ['err' => $err]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $output = $result->getValue();
        $resource = new UserListResource($output->users);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
