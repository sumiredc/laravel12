<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Hash;

final class Password
{
    private const LENGTH = 16;

    private const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private const LOWER = 'abcdefghijklmnopqrstuvwxyz';

    private const SYMBOLS = '!@#$%^&*()_+{}[]';

    private const NUMBERS = '0123456789';

    public readonly string $hashed;

    public static function make(): self
    {
        $chars = self::UPPER . self::LOWER . self::SYMBOLS . self::NUMBERS;
        $upper = str_shuffle(self::UPPER)[0];
        $lower = str_shuffle(self::LOWER)[0];
        $symbol = str_shuffle(self::SYMBOLS)[0];
        $number = str_shuffle(self::NUMBERS)[0];

        $base = mb_substr(str_shuffle(str_repeat($chars, self::LENGTH)), 0, self::LENGTH - 4);
        $plain = str_shuffle($base . $upper . $lower . $symbol . $number);

        return new self($plain);
    }

    public function __construct(public readonly string $plain)
    {
        $this->hashed = Hash::make($plain);
    }
}
