<?php

declare(strict_types=1);

namespace App\Casts\Role;

use App\Consts\Role;
use App\ValueObjects\Role\RoleID;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class RoleIDCast implements CastsAttributes
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): RoleID
    {
        $role = Role::from($value);

        return RoleID::parse($role);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($value instanceof RoleID) {
            return $value->value;
        }

        throw new InvalidArgumentException;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): string
    {
        return strval($value);
    }
}
