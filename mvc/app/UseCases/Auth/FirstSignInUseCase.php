<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Requests\Auth\FirstSignInRequestInterface;
use App\Http\Resources\Auth\SignInUserResource;
use App\Repositories\TokenRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\ValueObjects\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function is_null;

final class FirstSignInUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenRepositoryInterface $tokenRepository
    ) {}

    public function __invoke(FirstSignInRequestInterface $request): SignInUserResource
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

            $user = $this->userRepository->update(
                user: $user,
                hashedPassword: $password->hashed,
            );

            $this->userRepository->verifyEmail($user);

            $token = $this->tokenRepository->createUserAuthorizationToken($user);

            return new SignInUserResource($user, $token);
        });
    }
}
