<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\Password;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialException;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class FirstSignInUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenRepositoryInterface $tokenRepository
    ) {}

    /** @return Result<FirstSignInOutput,InvalidCredentialException|InternalServerErrorException> */
    public function __invoke(FirstSignInInput $input): Result
    {
        $result = $this->userRepository->getUnverifiedByEmailWithPassword($input->loginID);
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

        $newPassword = new Password($input->newPassword);

        DB::beginTransaction();

        $result = $this->userRepository->updatePasswordAndVerifyEmail($user, $newPassword);
        if ($result->isErr()) {
            DB::rollBack();

            return $result;
        }

        $result = $this->tokenRepository->createUserAuthorizationToken($user->userID);
        if ($result->isErr()) {
            DB::rollBack();

            return $result;
        }

        $token = $result->getValue();
        $output = new FirstSignInOutput($user, $token);
        DB::commit();

        return Result::ok($output);
    }
}
