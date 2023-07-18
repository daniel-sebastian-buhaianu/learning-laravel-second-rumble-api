<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Support\Str;
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
        $title = fake()->sentence();
        $titleSlug = Str::of($title)->slug('-');
        $id = 'v3ab0c1-' .  $titleSlug;

        return [
            'id' => $id,
            'channel_id' => Channel::inRandomOrder()->first()->id,
            'url' => 'https://rumble.com/' . $id . '.html',
            'src' => 'https://rumble.com/video/' . $id,
            'title' => $title,
            'description' => fake()->text(),
            'likes_count' => fake()->numberBetween(0, 1000),
            'dislikes_count' => fake()->numberBetween(0, 1000),
            'comments_count' => fake()->numberBetween(0, 1000),
            'uploaded_at' => fake()->date()
        ];
    }
}
