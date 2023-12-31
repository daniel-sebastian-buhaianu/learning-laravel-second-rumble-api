<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Channel>
 */
class ChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id = fake()->unique()->word();

        return [
            'id' => $id,
            'url' => 'https://rumble.com/c/' . $id,
            'name' => fake()->unique()->text(20),
            'joined_at' => fake()->date()
        ];
    }
}
