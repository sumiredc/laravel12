<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\Test\OAuthClientSeeder;

\beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        OAuthClientSeeder::class,
    ]);

    $user = User::factory()->userRole()->create();

    $this->userID = $user->id;
    $this->adminToken = $this->createAdmin()->getAdminToken();
    $this->userToken = $this->createUser()->getUserToken();
});

\describe('PUT /api/user/{user}', function () {
    \it('returns a response 200', function () {
        $response = $this->putJson(
            uri: "/api/user/$this->userID",
            data: [
                'name' => 'updated name',
                'email' => 'updated@xxx.xxx',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(200);
    });

    \it('returns a response 200 when a signed-in user', function () {
        $user = User::factory()->userRole()->create([
            'email' => 'user@uuu.uuu',
            'password' => Hash::make('Password@1234567890'),
        ]);

        $response = $this->postJson(
            uri: '/api/sign-in',
            data: [
                'login_id' => 'user@uuu.uuu',
                'password' => 'Password@1234567890',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        );

        $token = $response->json('token');

        $response = $this->putJson(
            uri: "/api/user/$user->id",
            data: [
                'name' => 'updated name',
                'email' => 'updated@xxx.xxx',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token",
            ]);

        $response->assertStatus(200);
    });

    \it('returns a response 401', function () {
        $response = $this->putJson(
            uri: "/api/user/$this->userID",
            data: [],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(401);
    });

    \it('returns a response 403', function () {
        $response = $this->putJson(
            uri: "/api/user/$this->userID",
            data: [],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->userToken",
            ]);

        $response->assertStatus(403);
    });

    \it('returns a response 422', function () {
        $response = $this->putJson(
            uri: "/api/user/$this->userID",
            data: [],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(422);
    });
});
