<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Entities\UserSearchParams;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\HashedPassword;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;

interface UserRepositoryInterface
{
    /** @return Result<list<User>,InternalServerErrorException> */
    public function list(UserSearchParams $params): Result;

    /** @return Result<User,InternalServerErrorException> */
    public function create(User $user, Password $password): Result;

    /** @return Result<?User,InternalServerErrorException> */
    public function get(UserID $userID): Result;

    /** @return Result<bool,InternalServerErrorException> */
    public function existsByEmail(string $email, ?UserID $ignoreUserID = null): Result;

    /** @return Result<array{0:User,1:HashedPassword},InternalServerErrorException|NotFoundException> */
    public function getByEmailWithPassword(string $email): Result;

    /** @return Result<array{0:User,1:HashedPassword},InternalServerErrorException|NotFoundException> */
    public function getUnverifiedByEmailWithPassword(string $email): Result;

    /** @return Result<User,InternalServerErrorException> */
    public function update(User $user, ?Password $password = null): Result;

    /** @return Result<null,InternalServerErrorException> */
    public function updatePasswordAndVerifyEmail(User $user, Password $password): Result;

    /** @return Result<int,InternalServerErrorException> */
    public function delete(UserID $userID): Result;
}
