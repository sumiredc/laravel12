<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\AuthorizeTrait;
use Illuminate\Foundation\Http\FormRequest;

final class SignInRequest extends FormRequest
{
    use AuthorizeTrait;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'max:1000'],
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
