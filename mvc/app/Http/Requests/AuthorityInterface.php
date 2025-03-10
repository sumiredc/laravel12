<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Exceptions\UnauthorizedException;
use App\Models\User;

interface AuthorityInterface
{
    /**
     * @throws UnauthorizedException
     */
    public function userOrFail(): User;

    public function userOrNull(): ?User;
}
