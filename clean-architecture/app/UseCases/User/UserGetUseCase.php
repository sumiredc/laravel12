<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;

use function is_null;

final class UserGetUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /** @return Result<UserGetOutput,NotFoundException|InternalServerErrorException> */
    public function __invoke(UserGetInput $input): Result
    {
        $result = $this->userRepository->get($input->userID);
        if ($result->isErr()) {
            return $result;
        }

        $user = $result->getValue();
        if (is_null($user)) {
            return Result::err(new NotFoundException);
        }

        $output = new UserGetOutput($user);

        return Result::ok($output);
    }
}
