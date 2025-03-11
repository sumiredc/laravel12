<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Consts\RoleID;
use App\Models\Role;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::factory()->create([
            'id' => RoleID::Admin->value,
            'name' => 'Admin',
        ]);

        Role::factory()->create([
            'id' => RoleID::User->value,
            'name' => 'User',
        ]);
    }
}
