<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstSignInRequest;
use App\UseCases\Auth\FirstSignInUseCase;
use Illuminate\Http\JsonResponse;
use Throwable;

final class FirstSignInController extends Controller
{
    public function __invoke(FirstSignInRequest $request): JsonResponse
    {
        try {
            $useCase = app(FirstSignInUseCase::class);
            $resource = $useCase($request);

            return new JsonResponse($resource, JsonResponse::HTTP_OK);
        } catch (InvalidCredentialException $ex) {
            throw $ex;
        } catch (Throwable $th) {
            report($th);
            throw new InvalidCredentialException;
        }
    }
}
