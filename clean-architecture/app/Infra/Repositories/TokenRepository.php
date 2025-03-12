<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Consts\PersonalAccessTokenName;
use App\Domain\Entities\AuthUser;
use App\Domain\Repositories\TokenRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\OAuthToken;
use App\Domain\ValueObjects\UserID;
use App\Models\User;
use Laravel\Passport\Passport;
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
            $token = new OAuthToken($tokenResult->accessToken);

            return Result::ok($token);
        } catch (Throwable $th) {
            $err = new InternalErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function revokeUserAuthorizationToken(AuthUser $auth): Result
    {
        try {
            Passport::token()->where('id', $auth->personalAccessToken->tokenID->value)
                ->update(['revoked' => true]);

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
