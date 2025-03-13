<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Shared\Result;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

final class HashedPassword
{
    private function __construct(public readonly string $value) {}

    public static function parse(string $value): Result
    {
        if (Hash::isHashed($value)) {
            return Result::ok(new self($value));
        }

        return Result::err(new InvalidArgumentException);
    }
}
