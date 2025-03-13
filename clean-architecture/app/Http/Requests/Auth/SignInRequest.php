<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\AuthorizeTrait;
use App\Rules\Auth\CredentialStringRule;

final class SignInRequest extends AbstractRequest
{
    use AuthorizeTrait;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => ['required', app(CredentialStringRule::class)],
            'password' => ['required', app(CredentialStringRule::class)],
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
}
