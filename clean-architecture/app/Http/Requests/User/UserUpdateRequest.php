<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domain\Consts\RoleID;
use App\Http\Requests\AbstractRequest;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;

final class UserUpdateRequest extends AbstractRequest
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

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', app(UserNameRule::class)],
            'email' => ['required', app(UserEmailRule::class)],
        ];
    }

    public function name(): string
    {
        return $this->validated('name', '');
    }

    public function email(): string
    {
        return $this->validated('email', '');
    }
}
