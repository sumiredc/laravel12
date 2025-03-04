<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeTrait;
use Illuminate\Foundation\Http\FormRequest;

final class UserGetRequest extends FormRequest
{
    use AuthorizeTrait;

    public function authorize(): bool
    {
        return $this->can('get', [$this->route('user')]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
