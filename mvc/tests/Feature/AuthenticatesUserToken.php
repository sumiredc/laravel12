<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait AuthenticatesUserToken
{
    private const ADMIN_EMAIL = 'xxx@admin.xxx';

    private const ADMIN_PASSWORD = 'Password@123456789';

    private const USER_EMAIL = 'xxx@user.xxx';

    private const USER_PASSWORD = 'Password@123456789';

    private const API_REQUEST_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    private function createAdmin(): self
    {
        User::factory()->adminRole()->create([
            'email' => self::ADMIN_EMAIL,
            'password' => Hash::make(self::ADMIN_PASSWORD),
        ]);

        return $this;
    }

    private function createUser(): self
    {
        User::factory()->userRole()->create([
            'email' => self::USER_EMAIL,
            'password' => Hash::make('Password@123456789'),
        ]);

        return $this;
    }

    private function getAdminToken(): string
    {
        $response = $this->postJson(uri: '/api/sign-in',
            data: [
                'login_id' => self::ADMIN_EMAIL,
                'password' => self::ADMIN_PASSWORD,
            ],
            headers: self::API_REQUEST_HEADERS
        );

        return $response->json('token');
    }

    private function getUserToken(): string
    {
        $response = $this->postJson(uri: '/api/sign-in',
            data: [
                'login_id' => self::USER_EMAIL,
                'password' => self::USER_PASSWORD,
            ],
            headers: self::API_REQUEST_HEADERS
        );

        return $response->json('token');
    }
}
