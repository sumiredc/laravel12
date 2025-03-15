<?php

declare(strict_types=1);

use App\Models\User;
use App\UseCases\Auth\FirstSignInUseCase;
use Database\Seeders\RoleSeeder;
use Database\Seeders\Test\OAuthClientSeeder;
use Illuminate\Support\Facades\Hash;

\beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        OAuthClientSeeder::class,
    ]);

    User::factory()->userRole()->unverified()->create([
        'email' => 'unverified@user.xxx',
        'password' => Hash::make('Password@123456789'),
    ]);
});

\describe('POST /api/first-sign-in', function () {
    \it('returns a response 200', function () {
        $response = $this->postJson(
            uri: '/api/first-sign-in',
            data: [
                'login_id' => 'unverified@user.xxx',
                'password' => 'Password@123456789',
                'new_password' => 'iXI%$JkR4raX4UxI',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(200);
    });

    \it('returns a response 401 when credentials are incorrect', function ($loginID, $password) {
        $response = $this->postJson(
            uri: '/api/first-sign-in',
            data: [
                'login_id' => $loginID,
                'password' => $password,
                'new_password' => 'iXI%$JkR4raX4UxI',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(401);
    })
        ->with([
            'invalid password' => ['unverified@user.xxx', 'UnMatchPassword@123456'],
            'invalid login id' => ['unmatch@user.xxx', 'Password@123456789'],
        ]);

    \it('returns a response 401 when internal server error', function () {
        $mock = Mockery::mock(new class
        {
            public function __invoke()
            {
                throw new Exception(code: 500);
            }
        });
        \app()->bind(FirstSignInUseCase::class, fn () => $mock);

        $response = $this->postJson(
            uri: '/api/first-sign-in',
            data: [
                'login_id' => 'unverified@user.xxx',
                'password' => 'Password@123456789',
                'new_password' => 'iXI%$JkR4raX4UxI',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(401);
    });

    \it('returns a response 422 when validation are incorrect', function () {
        $response = $this->postJson(
            uri: '/api/first-sign-in',
            data: [],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(422);
    });

});
