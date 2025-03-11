<?php

declare(strict_types=1);

namespace App\InterfaceAdapter\Validators\Auth;

use App\InterfaceAdapter\Validators\Validator;

final class SignInValidator extends Validator
{
    public function rules(): array
    {
        return [
            'login_id' => ['required'],
            'password' => ['required'],
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
