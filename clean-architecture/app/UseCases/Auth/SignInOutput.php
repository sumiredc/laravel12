<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\PersonalAccessToken;

final class SignInOutput
{
    public function __construct(public readonly User $user, public readonly PersonalAccessToken $token) {}
}
