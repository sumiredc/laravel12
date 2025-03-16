<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use JsonSerializable;

final class OAuthTokenID implements JsonSerializable
{
    private function __construct(public readonly string $value) {}

    public static function parse(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
