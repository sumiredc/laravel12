<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Entities\UserSearchParams;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Exceptions\InternalServerErrorException;

final class UserListUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /** @return Result<UserListOutput,InternalServerErrorException> */
    public function __invoke(UserListInput $input): Result
    {
        $params = new UserSearchParams($input->name, $input->email, $input->offset, $input->limit);
        $result = $this->userRepository->list($params);
        if ($result->isErr()) {
            return $result;
        }

        $users = $result->getValue();
        $output = new UserListOutput($users);

        return Result::ok($output);
    }
}
