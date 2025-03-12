<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Entities\AuthUser;

final class SignOutInput
{
    public function __construct(public readonly AuthUser $user) {}
}
