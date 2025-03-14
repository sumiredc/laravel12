<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domain\Consts\RoleID;
use App\Http\Requests\AbstractRequest;

final class UserDeleteRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        $auth = $this->authUserOrNull();

        if (is_null($auth)) {
            return false;
        }

        return (bool) ($auth->roleID === RoleID::Admin);
    }
}
