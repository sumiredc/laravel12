<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\SignInUserResource;
use App\Http\Resources\ValidationErrorResource;
use App\Http\Responses\ErrorResponse;
use App\InterfaceAdapter\Validators\Auth\SignInValidator;
use App\UseCases\Auth\SignInInput;
use App\UseCases\Auth\SignInUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class SignInController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = SignInValidator::make($request);

        if ($validator->fails()) {
            $resource = new ValidationErrorResource($validator->errors()->toArray());

            return new JsonResponse($resource);
        }

        $safe = $validator->safe();
        $input = new SignInInput($safe->input('login_id'), $safe->input('password'));
        $useCase = app(SignInUseCase::class);

        $result = $useCase($input);

        if ($result->isErr()) {
            Log::error('failed sign in', ['ex' => $result->getError()]);

            return new ErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $output = $result->getValue();
        $resource = new SignInUserResource($output->user, $output->token);

        return new JsonResponse($resource, JsonResponse::HTTP_OK);
    }
}
