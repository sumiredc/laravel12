<?php

declare(strict_types=1);

namespace App\InterfaceAdapter\Validators\User;

use App\InterfaceAdapter\Validators\Validator;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;

final class UserCreateValidator extends Validator
{
    public function rules(): array
    {
        return [
            'name' => ['required', app(UserNameRule::class)],
            'email' => ['required', app(UserEmailRule::class)],
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
