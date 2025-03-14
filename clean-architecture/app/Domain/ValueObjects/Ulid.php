<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Shared\Result;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\Uid\Ulid as UidUlid;
use Throwable;

abstract class Ulid implements JsonSerializable
{
    private function __construct(public readonly string $value) {}

    final public static function make(): static
    {
        $ulid = new UidUlid;

        return new static($ulid->toString());
    }

    /** @return Result<static,InvalidArgumentException> */
    final public static function parse(string $value): Result
    {
        try {
            $ulid = new UidUlid($value);

            return Result::ok(new static($ulid->toString()));
        } catch (Throwable $th) {
            return Result::err($th);
        }

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
