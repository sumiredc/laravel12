<?php

declare(strict_types=1);

use App\Rules\User\UserEmailRule;
use Illuminate\Support\Facades\Validator;

\describe('validate', function () {
    \it('accepts valid email addresses', function ($v) {
        $rule = new UserEmailRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->passes())->toBeTrue();
    })
        ->with([
            'simple valid email' => 'abc@xxx.xxx',
            'maximum max-length email' => \sprintf('%s@xxx.xxx', \str_repeat('a', 55)),
        ]);

    \it('rejects invalid values', function ($v) {
        $rule = new UserEmailRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->fails())->toBeTrue();
    })
        ->with([
            'email with invalid character' => 'aaa#xxx.xxx',
            'email exceeding maximum length' => \sprintf('%s@xxxxx.xxx', \str_repeat('a', 91)),
        ]);
});
