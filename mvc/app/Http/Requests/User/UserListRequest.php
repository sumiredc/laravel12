<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeTrait;
use App\Models\User;
use App\Rules\Common\PositiveNaturalNumberRule;
use Illuminate\Foundation\Http\FormRequest;

final class UserListRequest extends FormRequest
{
    use AuthorizeTrait;

    public function authorize(): bool
    {
        return $this->can('list', [User::class]);
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
        return intval($this->validated('limit', 1) ?? 1);
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
