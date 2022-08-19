<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pterodactyl_id' => $this->faker->randomNumber(4),
            'user_id' => 1,
            'identifier' => $this->faker->uuid,
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'status' => $this->faker->boolean,
            'suspended' => 0,
            'memory' => $this->faker->randomNumber(4),
            'cpu' => $this->faker->randomNumber(4),
            'swap' => $this->faker->randomNumber(4),
            'disk' => $this->faker->randomNumber(4),
            'io' => $this->faker->randomNumber(4),
            'databases' => $this->faker->randomNumber(1),
            'backups' => $this->faker->randomNumber(1),
            'allocations' => $this->faker->randomNumber(1),
            'threads' => $this->faker->randomNumber(1),
            'oom_disabled' => $this->faker->boolean,
            'node' => 1,
            'allocation' => $this->faker->randomNumber(4),
            'nest' => 1,
            'egg' => 1,
            'price' => $this->faker->randomFloat(3),
        ];
    }
}
