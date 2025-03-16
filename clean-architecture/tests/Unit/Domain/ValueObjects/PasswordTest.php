<?php

declare(strict_types=1);

use App\Domain\ValueObjects\Password;
use Illuminate\Support\Facades\Hash;

\describe('make', function () {
    \it('includes at least one uppercase letter', function () {
        $result = Password::make();
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        \expect(\strpbrk($result->plain, $upper) !== false)->toBeTrue();
    });

    \it('includes at least one lowercase letter', function () {
        $result = Password::make();
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        \expect(\strpbrk($result->plain, $lower) !== false)->toBeTrue();
    });

    \it('includes at least one symbol', function () {
        $result = Password::make();
        $symbol = '!@#$%^&*()_+{}[]';
        \expect(\strpbrk($result->plain, $symbol) !== false)->toBeTrue();
    });

    \it('includes at least one number', function () {
        $result = Password::make();
        $numbers = '0123456789';
        \expect(\strpbrk($result->plain, $numbers) !== false)->toBeTrue();
    });

    \it('has the correct length', function () {
        $result = Password::make();
        \expect(\mb_strlen($result->plain))->toBe(16);
    });

    \it('hash matches the plain text password', function () {
        $result = Password::make();
        \expect(Hash::check($result->plain, $result->hashed))->toBeTrue();
    });
});
