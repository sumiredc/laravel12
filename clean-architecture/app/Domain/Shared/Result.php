<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use RuntimeException;

/**
 * @template V
 * @template E
 */
final class Result
{
    /**
     * @param V $value
     * @param E $error
     */
    private function __construct(
        private bool $isSuccess, private $value = null, private $error = null
    ) {}

    /** @param V $value */
    public static function ok($value): self
    {
        return new self(true, $value);
    }

    /** @param E $error */
    public static function err($error): self
    {
        return new self(false, null, $error);
    }

    public function isOk(): bool
    {
        return $this->isSuccess;
    }

    public function isErr(): bool
    {
        return !$this->isSuccess;
    }

    /** @return V */
    public function getValue()
    {
        if (!$this->isSuccess) {
            throw new RuntimeException('Cannot get the value of a failed result.');
        }

        return $this->value;
    }

    /** @return E */
    public function getError()
    {
        if ($this->isSuccess) {
            throw new RuntimeException('Cannot get the error of a successful result.');
        }

        return $this->error;
    }
}
