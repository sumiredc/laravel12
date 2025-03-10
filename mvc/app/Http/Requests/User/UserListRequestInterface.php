<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

interface UserListRequestInterface
{
    public function offset(): int;

    public function limit(): int;

    public function name(): string;

    public function email(): string;
}
