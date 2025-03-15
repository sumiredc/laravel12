<?php

declare(strict_types=1);

use Database\Seeders\RoleSeeder;
use Database\Seeders\Test\OAuthClientSeeder;

\beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        OAuthClientSeeder::class,
    ]);

    $this->token = $this->createUser()->getUserToken();
});

\describe('DELETE /api/sign-out', function () {
    \it('returns a response 204', function () {
        $response = $this->deleteJson(
            uri: '/api/sign-out',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->token",
            ]);

        $response->assertStatus(204);
    });

    \it('returns a response 401', function () {
        $response = $this->deleteJson(
            uri: '/api/sign-out',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(401);
    });
});
