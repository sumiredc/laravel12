<?php

declare(strict_types=1);

use App\Rules\User\UserEmailRule;
use Illuminate\Support\Facades\Validator;

// TODO: Feature to Unit
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

    // it('rejects invalid duplicate email', function () {
    //     $email = 'aaa@xxx.xxx';

    //     User::factory()->create([
    //         'id' => UserID::make(),
    //         'role_id' => RoleID::parse(Role::User),
    //         'name' => 'test name',
    //         'email' => 'aaa@xxx.xxx',
    //         'password' => 'password',
    //     ]);

    //     $rule = new UserEmailRule;
    //     $validator = Validator::make(
    //         data: ['v' => $email],
    //         rules: ['v' => $rule]
    //     );

    //     expect($validator->fails())->toBeTrue();
    // });
});
