<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Requests\Auth\FirstSignInRequest;
use App\Http\Resources\Auth\SignInUserResource;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\ValueObjects\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class FirstSignInUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenRepository $tokenRepository
    ) {}

    public function __invoke(FirstSignInRequest $request): SignInUserResource
    {
        $user = $this->userRepository->getUnverifiedByEmail($request->loginID());
        if (
            is_null($user)
            || !Hash::check($request->password(), $user->password)
        ) {
            throw new InvalidCredentialException;
        }

        return DB::transaction(function () use ($user, $request) {
            $password = new Password($request->newPassword());

            $this->userRepository->update(
                user: $user,
                hashedPassword: $password->hashed
            );

            $this->tokenRepository->deleteUserAuthorizationToken($user);
            $token = $this->tokenRepository->createUserAuthorizationToken($user);

            return new SignInUserResource($user, $token);
        });
    }
}
