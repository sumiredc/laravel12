<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\Entities\User;

final class UserGetOutput
{
    public function __construct(public readonly User $user) {}
}
