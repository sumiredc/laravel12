<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use JsonSerializable;

final class OAuthToken implements JsonSerializable
{
    public function __construct(public readonly string $value) {}

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
