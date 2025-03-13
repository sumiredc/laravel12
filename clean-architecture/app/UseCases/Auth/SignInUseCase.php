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
use Throwable;

final class SignInUseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /** @return Result<SignInOutPut,InvalidCredentialException,InternalServerErrorException> */
    public function __invoke(SignInInput $input): Result
    {
        $result = $this->userRepository->getByEmailWithPassword($input->loginID);
        if ($result->isErr()) {
            return $result;
        }

        [$user, $password] = $result->getValue();

        if (
            is_null($user)
            || is_null($password)
            || !Hash::check($input->password, $password->value)
        ) {
            return Result::err(new InvalidCredentialException);
        }

        try {
            $token = DB::transaction(function () use ($user) {
                $result = $this->tokenRepository->createUserAuthorizationToken($user->userID);
                if ($result->isErr()) {
                    throw $result->getError();
                }

                return $result->getValue();
            });

            $output = new SignInOutput($user, $token);

            return Result::ok($output);
        } catch (Throwable $th) {
            return Result::err($th);
        }
    }
}
