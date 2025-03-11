<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class SignInUseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /** @return Result<SignInOutPut,InternalServerErrorException> */
    public function __invoke(SignInInput $input): Result
    {
        $result = $this->userRepository->getByEmail($input->loginID);
        if ($result->isErr()) {
            return $result;
        }

        $user = $result->getValue();

        if (
            is_null($user)
            || !Hash::check($input->password, $user->hashedPassword())
        ) {
            return Result::err(new InvalidCredentialException);
        }

        $result = DB::transaction(function () use ($user) {
            return $this->tokenRepository->createUserAuthorizationToken($user->userID);
        });

        if ($result->isErr()) {
            return $result;
        }
        $token = $result->getValue();

        $output = new SignInOutput($user, $token);

        return Result::ok($output);
    }
}
