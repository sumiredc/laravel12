<?php

declare(strict_types=1);

use Database\Seeders\RoleSeeder;
use Database\Seeders\Test\OAuthClientSeeder;

\beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        OAuthClientSeeder::class,
    ]);

    $this->adminToken = $this->createAdmin()->getAdminToken();
    $this->userToken = $this->createUser()->getUserToken();
});

\describe('POST /api/user', function () {
    \it('returns a response 201', function () {
        $response = $this->postJson(
            uri: '/api/user',
            data: [
                'name' => 'user name',
                'email' => 'new-user@zzz.xxx',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(201);
    });

    \it('returns a response 401', function () {
        $response = $this->postJson(
            uri: '/api/user',
            data: [
                'name' => 'user name',
                'email' => 'new-user@zzz.xxx',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(401);
    });

    \it('returns a response 403', function () {
        $response = $this->postJson(
            uri: '/api/user',
            data: [
                'name' => 'user name',
                'email' => 'new-user@zzz.xxx',
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->userToken",
            ]);

        $response->assertStatus(403);
    });

    \it('returns a response 422', function () {
        $response = $this->postJson(
            uri: '/api/user',
            data: [
            ],
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(422);
    });
});
