<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\OAuthToken;

final class SignInOutput
{
    public function __construct(public readonly User $user, public readonly OAuthToken $token) {}
}
