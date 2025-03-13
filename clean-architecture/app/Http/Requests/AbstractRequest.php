<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Entities\AuthUser;
use App\Domain\Entities\OAuthPersonalAccessToken;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\OAuthClientID;
use App\Domain\ValueObjects\OAuthTokenID;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\UnauthorizedException;
use App\Models\OAuthAccessToken;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;

abstract class AbstractRequest extends FormRequest
{
    /** @return Result<AuthUser,UnauthorizedException> */
    final public function authUser(): Result
    {
        $auth = $this->user();

        if (is_null($auth)) {
            $err = new UnauthorizedException;

            return Result::err($err);
        }
        $user = $this->userModelToDomain($auth);

        return Result::ok($user);
    }

    final public function authUserOrNull(): ?AuthUser
    {
        $auth = $this->user();

        if (is_null($auth)) {
            return null;
        }

        return $this->userModelToDomain($auth);
    }

    /** @throws InvalidArgumentException */
    private function userModelToDomain(User $user): AuthUser
    {
        $result = UserID::parse($user->id);

        // NOTE: NO ERROR - Refer to FW for value
        if ($result->isErr()) {
            throw $result->getError();
        }

        $userID = $result->getValue();

        /** @var OAuthAccessToken $tokenModel */
        $tokenModel = $user->token();
        $tokenID = OAuthTokenID::parse($tokenModel->id);
        $clientID = OAuthClientID::parse($tokenModel->client_id)->getValue();
        $personalAccessToken = new OAuthPersonalAccessToken($tokenID, $clientID);

        $auth = new AuthUser(
            userID: $userID,
            personalAccessToken: $personalAccessToken,
            name: $user->name,
            email: $user->email,
        );

        return $auth;
    }
}
