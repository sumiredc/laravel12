<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\AuthorizeTrait;
use App\Models\User;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserNameRule;

use function app;

final class UserCreateRequest extends AbstractRequest implements UserCreateRequestInterface
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

    public function name(): string
    {
        return $this->validated('name', '');
    }

    public function email(): string
    {
        return $this->validated('email', '');
    }
}
