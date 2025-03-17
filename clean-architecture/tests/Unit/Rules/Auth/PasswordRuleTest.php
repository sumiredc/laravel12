<?php

declare(strict_types=1);

use App\Rules\Auth\PasswordRule;
use Illuminate\Support\Facades\Validator;

\describe('validate', function () {
    \it('accepts valid passwords', function ($v) {
        $rule = new PasswordRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->passes())->toBeTrue();
    })
        ->with([
            'valid string in upper, lower, numbers and symbols' => 'Ab1@qqqqqqqqqqqq',
            'valid min-length(12) string' => '0*Kl12345678',
            'valid max-length(100) string' => \sprintf('Bc$2%s', \str_repeat('a', 96)),
        ]);

    \it('rejects invalid password', function ($v) {
        $rule = new PasswordRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->fails())->toBeTrue();
    })
        ->with([
            'invalid string less upper' => 'w4&aaaaaaaaa',
            'invalid string less lower' => '(R5123456789',
            'invalid string less numbers' => '#Mnaaaaaaaaa',
            'invalid string less symbols' => '134567890Ux',
            'invalid under-length(11) string' => 'aA1@ggggggg',
            'invalid over-length(101) string' => \sprintf('Bc$2%s', \str_repeat('a', 97)),
        ]);
});
