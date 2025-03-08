<?php

declare(strict_types=1);

use App\ValueObjects\Password;
use Illuminate\Support\Facades\Hash;

describe('Password::make()', function () {
    it('including to character of upper case', function () {
        $result = Password::make();
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        expect(strpbrk($result->plain, $upper) !== false)->toBeTrue();
    });

    it('including to character of lower case', function () {
        $result = Password::make();
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        expect(strpbrk($result->plain, $lower) !== false)->toBeTrue();
    });

    it('including to character of symbol', function () {
        $result = Password::make();
        $symbol = '!@#$%^&*()_+{}[]';
        expect(strpbrk($result->plain, $symbol) !== false)->toBeTrue();
    });

    it('including to character of number', function () {
        $result = Password::make();
        $numbers = '0123456789';
        expect(strpbrk($result->plain, $numbers) !== false)->toBeTrue();
    });

    it('match to length', function () {
        $result = Password::make();
        expect(mb_strlen($result->plain))->toBe(16);
    });

    it('match hash and plain', function () {
        $result = Password::make();
        expect(Hash::check($result->plain, $result->hashed))->toBeTrue();
    });
});
