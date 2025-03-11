<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Shared\Result;
use App\Domain\ValueObjects\PersonalAccessToken;
use App\Domain\ValueObjects\UserID;

interface TokenRepositoryInterface
{
    /** @return Result<PersonalAccessToken,InternalServerError> */
    public function createUserAuthorizationToken(UserID $userID): Result;

    /** @return Result<null,InternalServerError> */
    public function revokeUserAuthorizationToken(UserID $userID): Result;

    /** @return Result<null,InternalServerError> */
    public function deleteUserAuthorizationToken(UserID $userID): Result;
}
