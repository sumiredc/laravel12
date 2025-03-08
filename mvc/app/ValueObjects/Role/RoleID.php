<?php

declare(strict_types=1);

namespace App\ValueObjects\Role;

use App\Consts\Role;
use Symfony\Component\Uid\Ulid;

final class RoleID
{
    private function __construct(public readonly string $value) {}

    final public static function parse(Role $role): static
    {
        $ulid = new Ulid($role->value);

        return new self($ulid->toString());
    }

    final public function __toString(): string
    {
        return $this->value;
    }
}
