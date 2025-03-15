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

    User::factory(100)->userRole()->create();

    $this->adminToken = $this->createAdmin()->getAdminToken();
    $this->userToken = $this->createUser()->getUserToken();
});

\describe('GET /api/user', function () {
    \it('returns a response 200', function () {
        $response = $this->getJson(
            uri: '/api/user',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(200);
    });

    \it('returns a response 401', function () {
        $response = $this->getJson(
            uri: '/api/user',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(401);
    });

    \it('returns a response 403', function () {
        $response = $this->getJson(
            uri: '/api/user',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->userToken",
            ]);

        $response->assertStatus(403);
    });

    \it('returns a response 422', function () {
        $response = $this->getJson(
            uri: '/api/user?offset=-1',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(422);
    });
});
