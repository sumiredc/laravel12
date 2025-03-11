<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use JsonSerializable;
use Symfony\Component\Uid\Ulid as UidUlid;

abstract class Ulid implements JsonSerializable
{
    private function __construct(public readonly string $value) {}

    final public static function make(): static
    {
        $ulid = new UidUlid;

        return new static($ulid->toString());
    }

    final public static function parse(string $value): static
    {
        $ulid = new UidUlid($value);

        return new static($ulid->toString());
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
