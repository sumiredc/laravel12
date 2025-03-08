<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Rules\Auth\CredentialStringRule;
use App\Rules\Auth\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

final class FirstSignInRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => ['required', app(CredentialStringRule::class)],
            'password' => ['required', app(CredentialStringRule::class)],
            'new_password' => ['required', app(PasswordRule::class)],
        ];
    }

    public function loginID(): string
    {
        return $this->validated('login_id', '');
    }

    public function password(): string
    {
        return $this->validated('password', '');
    }

    public function newPassword(): string
    {
        return $this->validated('new_password', '');
    }
}
