<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domain\Consts\RoleID;
use App\Http\Requests\AbstractRequest;
use App\Rules\Common\PositiveNaturalNumberRule;

final class UserListRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        $auth = $this->authUserOrNull();

        if (is_null($auth)) {
            return false;
        }

        return (bool) ($auth->roleID === RoleID::Admin);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'offset' => ['nullable', app(PositiveNaturalNumberRule::class)],
            'limit' => ['nullable', app(PositiveNaturalNumberRule::class), 'max:100'],
            'name' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function offset(): int
    {
        return intval($this->validated('offset', 0));
    }

    public function limit(): int
    {
        return intval($this->validated('limit', 30) ?? 1);
    }

    public function name(): string
    {
        return $this->validated('name', '') ?? '';
    }

    public function email(): string
    {
        return $this->validated('email', '') ?? '';
    }
}
