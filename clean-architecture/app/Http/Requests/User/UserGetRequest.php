<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domain\Consts\RoleID;
use App\Http\Requests\AbstractRequest;

final class UserGetRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        $auth = $this->authUserOrNull();

        if (is_null($auth)) {
            return false;
        }

        if ($auth->roleID === RoleID::Admin) {
            return true;
        }

        return (bool) ($auth->userID->value === $this->route('userID'));
    }
}
