<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Exceptions\InvalidCredentialException;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Resources\Auth\SignInUserResource;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class SignInUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenRepository $tokenRepository
    ) {}

    public function __invoke(SignInRequest $request): SignInUserResource
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
