<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Requests\Auth\SignInRequestInterface;
use App\Http\Resources\Auth\SignInUserResource;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class SignInUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenRepositoryInterface $tokenRepository
    ) {}

    public function __invoke(SignInRequestInterface $request): SignInUserResource
    {
        $user = $this->userRepository->getByEmail($request->loginID());
        if (
            is_null($user)
            || !Hash::check($request->password(), $user->password)
        ) {
            throw new InvalidCredentialException;
        }

        return DB::transaction(function () use ($user) {
            $token = $this->tokenRepository->createUserAuthorizationToken($user);

            return new SignInUserResource($user, $token);
        });

    }
}
