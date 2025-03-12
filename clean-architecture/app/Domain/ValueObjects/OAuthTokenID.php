<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class OAuthTokenID
{
    private function __construct(public readonly string $value) {}

    final public static function parse(string $value): self
    {
        return new self($value);
    }

    final public function __toString(): string
    {
        return $this->value;
    }

    final public function jsonSerialize(): string
    {
        return $this->value;
    }
}
