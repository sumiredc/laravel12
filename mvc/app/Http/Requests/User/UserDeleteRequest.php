<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeTrait;
use Illuminate\Foundation\Http\FormRequest;

final class UserDeleteRequest extends FormRequest
{
    use AuthorizeTrait;

    public function authorize(): bool
    {
        return $this->can('delete', [$this->route('user')]);
    }
}
