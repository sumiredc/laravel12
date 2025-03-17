<?php

declare(strict_types=1);

use App\Rules\Auth\CredentialStringRule;
use Illuminate\Support\Facades\Validator;

\describe('validate', function () {
    \it('accepts vlaid credential strings', function ($v) {
        $rule = new CredentialStringRule;
        $validator = Validator::make(data: ['v' => $v], rules: ['v' => $rule]);

        \expect($validator->passes())->toBeTrue();
    })
        ->with([
            'valid empty string' => '',
            'valid short string' => 'sample text',
            'valid max-length string (1000 characters)' => \str_repeat('a', 1000),
        ]);

    \it('rejects invalid credential strings', function ($v) {
        $rule = new CredentialStringRule;
        $validator = Validator::make(data: ['v' => $v], rules: ['v' => $rule]);

        \expect($validator->fails())->toBeTrue();
    })
        ->with([
            'invalid over-length string (1001 characters)' => \str_repeat('a', 1001),
            'invalid number' => 10,
        ]);
});
