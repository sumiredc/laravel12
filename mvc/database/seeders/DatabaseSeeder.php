<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@xxx.xxx',
            'password' => '$2y$12$OfkdAurRQVuQQW8e.HbUKOGzRN.NWDwIXA9HNmi.QmVnzapm2cWuC',
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
