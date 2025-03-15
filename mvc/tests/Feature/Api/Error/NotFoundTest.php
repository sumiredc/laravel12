<?php

declare(strict_types=1);

\describe('ANY /api/not-found', function () {
    \it('returns a response 404 when GET method', function () {
        $response = $this->getJson(
            uri: '/api/not-found',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(404);
    });

    \it('returns a response 405 when POST method', function () {
        $response = $this->postJson(
            uri: '/api/not-found',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(405);
    });

    \it('returns a response 405 when PUT method', function () {
        $response = $this->postJson(
            uri: '/api/not-found',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(405);
    });

    \it('returns a response 405 when PATCH method', function () {
        $response = $this->postJson(
            uri: '/api/not-found',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(405);
    });

    \it('returns a response 405 when DELETE method', function () {
        $response = $this->postJson(
            uri: '/api/not-found',
            headers: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(405);
    });
});
