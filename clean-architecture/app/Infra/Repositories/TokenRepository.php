<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Consts\PersonalAccessTokenName;
use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\PersonalAccessToken;
use App\Domain\ValueObjects\UserID;
use App\Models\User;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Throwable;

final class TokenRepository implements TokenRepositoryInterface
{
    public function createUserAuthorizationToken(UserID $userID): Result
    {
        $user = new User;
        $user->id = $userID;

        try {
            $tokenResult = $user->createToken(PersonalAccessTokenName::UserAuthorization->value);
            $token = new PersonalAccessToken($tokenResult->accessToken);

            return Result::ok($token);
        } catch (Throwable $th) {
            $err = new InternalErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function revokeUserAuthorizationToken(UserID $userID): Result
    {
        $user = new User;
        $user->id = $userID;

        try {
            $user->token()->revoke();

            return Result::ok(null);
        } catch (Throwable $th) {
            $err = new InternalErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function deleteUserAuthorizationToken(UserID $userID): Result
    {
        $user = new User;
        $user->id = $userID;

        try {
            $user->tokens()->delete();

            return Result::ok(null);
        } catch (Throwable $th) {
            $err = new InternalErrorException(previous: $th);

            return Result::err($err);
        }
    }
}
