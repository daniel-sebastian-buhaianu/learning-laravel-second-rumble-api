<?php

namespace Database\Factories;

use Illuminate\Support\Str;
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
        $name = fake()->unique()->name();
        $id = Str::of($name)->slug('-');
        
        return [
            'id' => $id,
            'name' => $name,
            'description' => fake()->text(),
            'followers_count' => fake()->randomNumber(),
            'videos_count' => fake()->numberBetween(0, 1000),
            'joined_at' => fake()->date(),
        ];
    }
}
