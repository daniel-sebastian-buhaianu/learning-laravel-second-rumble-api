<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseUrl = 'https://rumble.com/';

        $id = fake()->unique()->word();

        return [
            'id' => $id,
            'channel_id' => Channel::factory()->create(),
            'url' => $baseUrl . $id,
            'src' => $baseUrl . $id . '/src',
            'name' => fake()->sentence(),
            'description' => fake()->text(200),
            'likes_count' => fake()->numberBetween(0, 1000),
            'dislikes_count' => fake()->numberBetween(0, 1000),
            'comments_count' => fake()->numberBetween(0, 1000),
            'views_count' => fake()->randomNumber(),
            'uploaded_at' => fake()->date()
        ];
    }
}
