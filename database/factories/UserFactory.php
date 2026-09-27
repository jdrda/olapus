<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Test-data factory, not the production administrator installer.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    // Keep the actual Olapus model namespace; do not move it during this upgrade.
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make(Str::random(48)),
            'remember_token' => Str::random(60),
            'active' => true,
        ];
    }
}
