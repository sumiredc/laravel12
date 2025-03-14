<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\OAuthToken;

final class FirstSignInOutput
{
    public function __construct(
        public readonly User $user,
        public readonly OAuthToken $token
    ) {}
}
