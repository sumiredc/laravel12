<?php

declare(strict_types=1);

use App\Rules\Common\PositiveNaturalNumberRule;
use Illuminate\Support\Facades\Validator;

describe('PositiveNaturalNumberRule', function () {
    it('allows valid number', function ($v) {
        $rule = new PositiveNaturalNumberRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        expect($validator->passes())->toBeTrue();
    })
        ->with([
            'zero' => 0,
            'php int max' => PHP_INT_MAX,
        ]);

    it('rejects invalid number', function ($v) {
        $rule = new PositiveNaturalNumberRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        expect($validator->fails())->toBeTrue();
    })
        ->with([
            'negative number' => -1,
            'over number' => PHP_INT_MAX + 1,
        ]);
});
