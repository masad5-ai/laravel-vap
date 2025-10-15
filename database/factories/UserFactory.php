<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'avatar' => fake()->imageUrl(300, 300, 'people'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'marketing_opt_in' => fake()->boolean(40),
            'default_billing_address' => [
                'line1' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->stateAbbr(),
                'postcode' => fake()->postcode(),
                'country' => 'Australia',
            ],
            'default_shipping_address' => [
                'line1' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->stateAbbr(),
                'postcode' => fake()->postcode(),
                'country' => 'Australia',
            ],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user): void {
            $defaultRole = Role::query()->where('is_default', true)->first();

            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        });
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user): void {
            $role = Role::firstOrCreate(
                ['slug' => 'admin'],
                [
                    'name' => 'Administrator',
                    'description' => 'Auto generated administrator role',
                    'is_default' => false,
                ],
            );
            $user->assignRole($role);
        });
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
