<?php

declare(strict_types=1);

use App\Rules\User\UserEmailRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\PresenceVerifierInterface;

describe('UserEmailRule', function () {
    it('allows valid email', function ($v) {
        $rule = new UserEmailRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        expect($validator->passes())->toBeTrue();
    })
        ->with([
            'valid email' => 'abc@xxx.xxx',
            'valid max-length email' => sprintf('%s@xxx.xxx', str_repeat('a', 55)),
        ]);

    it('rejects invalid email', function ($v) {
        $rule = new UserEmailRule;
        $validator = Validator::make(
            data: ['v' => $v],
            rules: ['v' => $rule]
        );

        expect($validator->fails())->toBeTrue();
    })
        ->with([
            'invalid email' => 'aaa#xxx.xxx',
            'valid over-length(101) email' => sprintf('%s@xxxxx.xxx', str_repeat('a', 91)),
        ]);

    it('rejects invalid duplicate email', function () {
        $presenceVerifierMock = Mockery::mock(PresenceVerifierInterface::class);
        $presenceVerifierMock->shouldReceive('getCount')
            ->andReturn(1);
        $presenceVerifierMock->shouldReceive('setConnection');
        Validator::setPresenceVerifier($presenceVerifierMock);

        $email = 'aaa@xxx.xxx';
        $rule = new UserEmailRule;
        $validator = Validator::make(
            data: ['v' => $email],
            rules: ['v' => $rule]
        );

        expect($validator->fails())->toBeTrue();
    });
});
