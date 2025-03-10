<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

interface UserCreateRequestInterface
{
    public function name(): string;

    public function email(): string;
}
