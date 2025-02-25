<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Models\User;

final class UserCreateUseCase
{
    public function __construct()
    {
        //
    }

    public function __invoke(): User
    {
        return new User();
    }
}
