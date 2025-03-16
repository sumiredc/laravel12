<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Shared\Result;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\Uid\Uuid as UidUuid;
use Throwable;

abstract class Uuid implements JsonSerializable
{
    private function __construct(public readonly string $value) {}

    /** @return Result<static,InvalidArgumentException> */
    final public static function parse(string $value): Result
    {
        try {
            $uuid = new UidUuid($value);

            return Result::ok(new static($uuid->toString()));
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
