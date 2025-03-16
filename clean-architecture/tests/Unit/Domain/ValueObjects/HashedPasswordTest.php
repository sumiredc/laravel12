<?php

declare(strict_types=1);

use App\Domain\ValueObjects\HashedPassword;
use Illuminate\Support\Facades\Hash;

\describe('parse', function () {
    \it('returns an Ok result', function () {
        $password = Hash::make('password');

        $result = HashedPassword::parse($password);

        \expect($result->isOk())->toBeTrue();
        \expect($result->isErr())->not->toBeTrue();
        \expect($result->getValue()->value)->toBe($password);
    });

    \it('returns an Err result', function () {
        $result = HashedPassword::parse('password');

        \expect($result->isErr())->toBeTrue();
        \expect($result->isOk())->not->toBeTrue();
        \expect($result->getError()::class)->toBe(InvalidArgumentException::class);
    });
});
