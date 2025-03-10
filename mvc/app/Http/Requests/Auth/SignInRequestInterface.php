<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

interface SignInRequestInterface
{
    public function loginID(): string;

    public function password(): string;
}
