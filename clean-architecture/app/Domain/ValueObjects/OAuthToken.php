<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use JsonSerializable;

final class OAuthToken implements JsonSerializable
{
    public function __construct(public readonly string $value) {}

    final public function __toString(): string
    {
        return $this->value;
    }

    final public function jsonSerialize(): string
    {
        return $this->value;
    }
}
