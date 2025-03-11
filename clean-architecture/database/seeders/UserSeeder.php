<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Consts\RoleID;
use App\Domain\ValueObjects\UserID;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'id' => UserID::make()->value,
            'role_id' => RoleID::Admin->value,
            'name' => 'Admin',
            'email' => 'admin@xxx.xxx',
            'password' => '$2y$12$OfkdAurRQVuQQW8e.HbUKOGzRN.NWDwIXA9HNmi.QmVnzapm2cWuC', // password
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
