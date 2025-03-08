<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Consts\Role;
use App\Models\Role as ModelsRole;
use App\ValueObjects\Role\RoleID;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        ModelsRole::create([
            'id' => RoleID::parse(Role::Admin),
            'name' => 'Admin',
        ]);

        ModelsRole::create([
            'id' => RoleID::parse(Role::User),
            'name' => 'User',
        ]);
    }
}
