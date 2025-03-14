<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Repositories\MailRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\DB;

final class UserCreateUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MailRepositoryInterface $mailRepository,
    ) {}

    /** @return Result<UserCreateOutput,EmailAlreadyTakenException|InternalServerErrorException> */
    public function __invoke(UserCreateInput $input): Result
    {
        $result = $this->userRepository->existsByEmail($input->email);
        if ($result->isErr()) {
            return $result;
        }

        if ($result->getValue()) {
            $err = new EmailAlreadyTakenException;

            return Result::err($err);
        }

        $userID = UserID::make();
        $user = new User(
            userID: $userID,
            roleID: RoleID::User,
            name: $input->name,
            email: $input->email,
        );
        $password = Password::make();

        DB::beginTransaction();

        $result = $this->userRepository->create($user, $password);
        if ($result->isErr()) {
            DB::rollBack();

            return Result::err($result->getError());
        }

        $result = $this->mailRepository->sendInitialPassword($user, $password);

        $output = new UserCreateOutput($user);

        DB::commit();

        return Result::ok($output);

    }
}
