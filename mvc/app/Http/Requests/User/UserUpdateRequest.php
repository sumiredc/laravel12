<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\AuthorizeTrait;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;

final class UserUpdateRequest extends AbstractRequest implements UserUpdateRequestInterface
{
    use AuthorizeTrait;

    public function authorize(): bool
    {
        return $this->can('update', [$this->route('user')]);
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
