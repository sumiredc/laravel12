<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;

final class UserDeleteUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    /** @return Result<UserDeleteOutput,NotFoundException,InternalServerErrorException> */
    public function __invoke(UserDeleteInput $input): Result
    {
        DB::beginTransaction();

        // TODO: delete tokens

        $result = $this->userRepository->delete($input->userID);
        if ($result->isErr()) {
            DB::rollBack();

            return $result;
        }

        if ($result->getValue() === 0) {
            DB::rollBack();

            return Result::err(new NotFoundException);
        }

        $output = new UserDeleteOutput;

        DB::commit();

        return Result::ok($output);
    }
}
