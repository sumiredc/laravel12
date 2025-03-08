<?php

declare(strict_types=1);

use App\ValueObjects\Password;
use Illuminate\Support\Facades\Hash;

test('Password::make() generates a sequre password with required character types', function () {
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $symbol = '!@#$%^&*()_+{}[]';
    $numbers = '0123456789';

    $password = Password::make();

    expect(strpbrk($password->plain, $upper) !== false)->toBeTrue();
    expect(strpbrk($password->plain, $lower) !== false)->toBeTrue();
    expect(strpbrk($password->plain, $symbol) !== false)->toBeTrue();
    expect(strpbrk($password->plain, $numbers) !== false)->toBeTrue();
    expect(16)->toBe(mb_strlen($password->plain));
    expect(Hash::check($password->plain, $password->hashed));
});
