<?php

declare(strict_types=1);

use App\Rules\Common\PositiveNaturalNumberRule;
use Illuminate\Support\Facades\Validator;

\describe('validate', function () {
    \it('accepts valid positive natural numbers', function ($v) {
        $rule = new PositiveNaturalNumberRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->passes())->toBeTrue();
    })
        ->with([
            'zero (lower bound)' => 0,
            'maximum integer value' => PHP_INT_MAX,
        ]);

    \it('rejects invalid values', function ($v) {
        $rule = new PositiveNaturalNumberRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->fails())->toBeTrue();
    })
        ->with([
            'negative integer' => -1,
            'integer exceeding maximum' => PHP_INT_MAX + 1,
            'non-integer value' => 1.5,
        ]);
});
