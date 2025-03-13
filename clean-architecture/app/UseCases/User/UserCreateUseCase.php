<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\DB;

final class UserCreateUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /** @return Result<UserCreateOutput,InternalServerErrorException> */
    public function __invoke(UserCreateInput $input): Result
    {
        $userID = UserID::make();
        $user = new User(
            userID: $userID,
            roleID: RoleID::User,
            name: $input->name,
            email: $input->email,
        );
        $password = Password::make();

        return DB::transaction(function () use ($user, $password) {
            $result = $this->userRepository->create($user, $password);

            if ($result->isErr()) {
                $err = new InternalServerErrorException(previous: $result->getError());

                return Result::err($err);
            }

            $output = new UserCreateOutput($result->getValue());

            return Result::ok($output);
        });

    }
}
