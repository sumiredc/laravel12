<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeTrait;
use App\Models\User;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;
use Illuminate\Foundation\Http\FormRequest;

final class UserCreateRequest extends FormRequest
{
    use AuthorizeTrait;

    public function authorize(): bool
    {
        return $this->can('create', [User::class]);
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

    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
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
