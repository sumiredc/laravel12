<?php

declare(strict_types=1);

use App\Rules\Auth\CredentialStringRule;
use Illuminate\Support\Facades\Validator;

describe('CredentialStringRule', function () {
    it('allows valid credentials strings', function ($v) {
        $rule = new CredentialStringRule;
        $validator = Validator::make(data: ['v' => $v], rules: ['v' => $rule]);

        expect($validator->passes())->toBeTrue();
    })
        ->with([
            'valid empty string' => '',
            'valid short string' => 'sample text',
            'valid max-length(1000) string' => str_repeat('a', 1000),
        ]);

    it('rejects invalid credential strings', function ($v) {
        $rule = new CredentialStringRule;
        $validator = Validator::make(data: ['v' => $v], rules: ['v' => $rule]);

        expect($validator->fails())->toBeTrue();
    })
        ->with([
            'invalid over-length string' => str_repeat('a', 1001),
            'invalid number' => 10,
        ]);
});
