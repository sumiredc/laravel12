<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialException;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class SignInUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {}

    /** @return Result<SignInOutPut,InvalidCredentialException,InternalServerErrorException> */
    public function __invoke(SignInInput $input): Result
    {
        $result = $this->userRepository->getByEmailWithPassword($input->loginID);
        if ($result->isErr()) {
            if ($result->getError() instanceof NotFoundException) {
                return Result::err(new InvalidCredentialException);
            }

            return $result;
        }

        [$user, $password] = $result->getValue();

        if (!Hash::check($input->password, $password->value)) {
            return Result::err(new InvalidCredentialException);
        }

        DB::beginTransaction();

        $result = $this->tokenRepository->createUserAuthorizationToken($user->userID);
        if ($result->isErr()) {
            DB::rollBack();

            return $result;
        }

        $output = new SignInOutput($user, $result->getValue());
        DB::commit();

        return Result::ok($output);
    }
}
