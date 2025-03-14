<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;

final class UserUpdateUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /** @return Result<UserUpdateOutput,NotFoundException|EmailAlreadyTakenException|InternalServerErrorException> */
    public function __invoke(UserUpdateInput $input): Result
    {
        $result = $this->userRepository->existsByEmail($input->email, $input->userID);
        if ($result->isErr()) {
            return $result;
        }

        if ($result->getValue()) {
            $err = new EmailAlreadyTakenException;

            return Result::err($err);
        }

        $result = $this->userRepository->get($input->userID);
        if ($result->isErr()) {
            return $result;
        }

        $user = $result->getValue();
        if (is_null($user)) {
            return Result::err(new NotFoundException);
        }

        $user = $user->recontruct($input->name, $input->email);

        DB::beginTransaction();

        $result = $this->userRepository->update($user);
        if ($result->isErr()) {
            DB::rollBack();

            return $result;
        }

        $output = new UserUpdateOutput($result->getValue());

        DB::commit();

        return Result::ok($output);
    }
}
