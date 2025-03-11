<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Entities\UserSearchParams;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserID;

interface UserRepositoryInterface
{
    /** @return Result<list<User>,InternalServerErrorException> */
    public function list(UserSearchParams $params): Result;

    /** @return Result<User,InternalServerErrorException> */
    public function create(User $user, Password $password): Result;

    /** @return Result<?User,InternalServerErrorException|NotFoundException> */
    public function get(UserID $userID): Result;

    /** @return Result<?User,InternalServerErrorException|NotFoundException> */
    public function getByEmail(string $email): Result;

    /** @return Result<?User,InternalServerErrorException|NotFoundException> */
    public function getUnverifiedByEmail(string $email): Result;

    /** @return Result<User,InternalServerErrorException> */
    public function update(User $user, ?Password $password = null): Result;

    /** @return Result<null,InternalServerErrorException> */
    public function verifyEmail(User $user): Result;

    /** @return Result<null,InternalServerErrorException> */
    public function delete(User $user): Result;
}
