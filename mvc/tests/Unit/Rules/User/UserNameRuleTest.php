<?php

declare(strict_types=1);

use App\Rules\User\UserNameRule;

\describe('validate', function () {
    \it('accepts valid names', function ($v) {
        $rule = new UserNameRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->passes())->toBeTrue();
    })
        ->with([
            'valid name' => 'Jone Due',
            'valid max-length name' => \str_repeat('a', 100),
        ]);

    \it('rejects invalid values', function ($v) {
        $rule = new UserNameRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        \expect($validator->fails())->toBeTrue();
    })
        ->with([
            'valid over-length(101) name' => \str_repeat('a', 101),
        ]);
});
