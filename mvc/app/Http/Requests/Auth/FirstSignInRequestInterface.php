<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

interface FirstSignInRequestInterface
{
    public function loginID(): string;

    public function password(): string;

    public function newPassword(): string;
}
