<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\UseCases\Auth\SignInUseCase;
use Illuminate\Http\JsonResponse;
use Throwable;

use function app;
use function report;

final class SignInController extends Controller
{
    public function __invoke(SignInRequest $request): JsonResponse
    {
        try {
            $useCase = app(SignInUseCase::class);
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
