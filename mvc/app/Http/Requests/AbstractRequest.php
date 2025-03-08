<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Exceptions\InternalServerErrorException;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    /**
     * @throws UnauthorizedException
     */
    final public function userOrFail(): User
    {
        $user = $this->user();
        if ($user instanceof User) {
            return $user;
        }

        throw new UnauthorizedException;
    }

    final public function userOrNull(): ?User
    {
        $user = $this->user();
        if ($user instanceof User || is_null($user)) {
            return $user;
        }

        throw new InternalServerErrorException;
    }
}
