<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\Error\InternalServerErrorResource;
use App\UseCases\User\UserCreateUseCase;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

final class UserCreateController extends Controller
{
    public function __invoke(UserCreateRequest $request): JsonResponse
    {
        $useCase = app(UserCreateUseCase::class);

        try {
            $resource = $useCase($request);

            return Response::json($resource, JsonResponse::HTTP_CREATED);
        } catch (HttpResponseException $resEx) {
            throw $resEx;
        } catch (Exception $ex) {
            Log::error('failed to create user', ['exception' => $ex]);

            return Response::json(
                new InternalServerErrorResource,
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
