<?php

declare(strict_types=1);

namespace App\Casts\User;

use App\ValueObjects\User\UserID;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

use function is_null;
use function strval;

final class UserIDCast implements CastsAttributes
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): UserID
    {
        return UserID::parse($value);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value instanceof UserID) {
            return $value->value;
        }

        if (is_null($value)) {
            return $value;
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
