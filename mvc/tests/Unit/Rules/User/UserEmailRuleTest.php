<?php

declare(strict_types=1);

use App\Rules\User\UserEmailRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\PresenceVerifierInterface;

\beforeEach(function () {
    $this->presenceVerifierMock = Mockery::mock(PresenceVerifierInterface::class);
    $this->presenceVerifierMock->shouldReceive('setConnection');
    Validator::setPresenceVerifier($this->presenceVerifierMock);
});

\describe('validate', function () {
    \it('accepts valid email addresses', function ($v) {
        $this->presenceVerifierMock->shouldReceive('getCount')->andReturn(0);
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

    \it('rejects duplicate email addresses', function () {
        $this->presenceVerifierMock->shouldReceive('getCount')->andReturn(1);

        $email = 'aaa@xxx.xxx';
        $rule = new UserEmailRule;
        $validator = Validator::make(
            data: ['v' => $email],
            rules: ['v' => $rule]
        );

        \expect($validator->fails())->toBeTrue();
    });
});
