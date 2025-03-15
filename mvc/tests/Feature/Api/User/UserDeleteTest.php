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

\describe('DELETE /api/user/{user}', function () {
    \it('returns a response 204', function () {
        $response = $this->deleteJson(
            uri: "/api/user/$this->userID",
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->adminToken",
            ]);

        $response->assertStatus(204);
    });

    \it('returns a response 401', function () {
        $response = $this->deleteJson(
            uri: "/api/user/$this->userID",
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(401);
    });

    \it('returns a response 403', function () {
        $response = $this->deleteJson(
            uri: "/api/user/$this->userID",
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $this->userToken",
            ]);

        $response->assertStatus(403);
    });
});
