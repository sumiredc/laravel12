<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Consts\Role;
use App\ValueObjects\Role\RoleID;
use App\ValueObjects\User\UserID;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function fake;
use function now;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => UserID::make(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function adminRole(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => RoleID::parse(Role::Admin),
        ]);
    }

    public function userRole(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => RoleID::parse(Role::User),
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
