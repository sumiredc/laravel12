<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\AuthorizeTrait;

final class UserDeleteRequest extends AbstractRequest
{
    use AuthorizeTrait;

    public function authorize(): bool
    {
        return $this->can('delete', [$this->route('user')]);
    }
}
