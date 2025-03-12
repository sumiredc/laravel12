<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\AuthUser;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\OAuthToken;
use App\Domain\ValueObjects\UserID;

interface TokenRepositoryInterface
{
    /** @return Result<OAuthToken,InternalServerError> */
    public function createUserAuthorizationToken(UserID $userID): Result;

    /** @return Result<null,InternalServerError> */
    public function revokeUserAuthorizationToken(AuthUser $auth): Result;

    /** @return Result<null,InternalServerError> */
    public function deleteUserAuthorizationToken(UserID $userID): Result;
}
