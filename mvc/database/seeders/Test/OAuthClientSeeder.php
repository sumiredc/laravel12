<?php

declare(strict_types=1);

namespace Database\Seeders\Test;

use App\Models\OAuthClient;
use Illuminate\Database\Seeder;

final class OAuthClientSeeder extends Seeder
{
    public function run(): void
    {
        OAuthClient::factory()->create([
            'id' => '9e624fc3-630c-46ee-a2b5-f05ddc7bdea5',
            'secret' => 'BiwS1EkLnbyAYb802pFupRKNeCtroHFXMQvbJnJg',
            'personal_access_client' => true,
        ]);
    }
}
