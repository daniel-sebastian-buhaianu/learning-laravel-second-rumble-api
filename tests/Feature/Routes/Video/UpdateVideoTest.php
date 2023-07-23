<?php

namespace Tests\Feature\Routes\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateVideoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_update_a_video(): void
    {
        $video = Video::factory()->create();

        $attributes = [
            'name' => 'A video name'
        ];
        
        $response = $this->patch('api/videos/' . $video->id, $attributes);

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function a_user_cannot_update_a_video(): void
    {
        $user = User::factory()->create();

        $video = Video::factory()->create();

        $attributes = [
            'name' => 'A video name'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $attributes);
        
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function an_admin_can_update_a_video(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = Video::factory()->create();

        $attributes = [
            'src' => 'https://google.com',
            'name' => 'A new video name',
            'thumbnail' => 'https://google.com',
            'description' => 'Cool description',
            'likes_count' => 120,
            'dislikes_count' => 14,
            'comments_count' => 40,
            'views_count' => 102434
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $attributes);
        
        $response->assertJsonFragment($attributes);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_video_id(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = Video::factory()->create();

        $attributes = [
            'id' => 'some id'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $attributes);
        
        $response->assertJsonFragment([
            'id' => $video->id
        ]);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_video_channel_id(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = Video::factory()->create();

        $attributes = [
            'channel_id' => 'some-other-channel'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $attributes);
        
        $response->assertJsonFragment([
            'channel_id' => $video->channel_id
        ]);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_video_url(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = Video::factory()->create();

        $attributes = [
            'url' => 'https://google.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $attributes);
        
        $response->assertJsonFragment([
            'url' => $video->url
        ]);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_video_uploaded_date(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = video::factory()->create();

        $attributes = [
            'uploaded_at' => '2021-04-05'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $attributes);
        
        $response->assertJsonFragment([
            'uploaded_at' => $video->uploaded_at
        ]);
    }

    /**
     * @test
     * @dataProvider invalidAttributes
     */
    public function an_admin_cannot_update_a_video_with_invalid_attributes(array $invalidAttributes): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = Video::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/videos/' . $video->id, $invalidAttributes);
        
        $response->assertRedirect();
    }

    public function invalidAttributes(): array
    {
        return [
            [[
                'src' => '',
                'name' => '',
                'thumbnail' => '',
                'description' => '',
                'likes_count' => '',
                'comments_count' => '',
                'views_count' => '',
            ]],
            [[
                'src' => 'not a url',
                'name' => 'some name',
                'thumbnail' => '',
                'description' => '',
                'likes_count' => 'haha',
                'comments_count' => '',
                'views_count' => 'blaaah',
            ]],
            [[
                'src' => 'https://google.com',
                'name' => 'a name longer than 255 characters a name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 charactersa name longer than 255 characters',
                'thumbnail' => 'https://google.com?q=google is the best!!!!',
                'description' => 'loooooong description on repeat!! loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!loooooong description on repeat!!',
                'likes_count' => 65536,
                'comments_count' => 65534,
                'views_count' => -1,
            ]],
            [[
                'src' => 'https://google.com',
                'name' => 'more realistic',
                'thumbnail' => 'https://google.com',
                'description' => 'cooooool',
                'likes_count' => 0,
                'comments_count' => 65536,
                'views_count' => 0,
            ]],
            [[
                'src' => 'https://lookslikeaurlbutisinvalidforsure.com',
                'name' => 'more realistic',
                'thumbnail' => 'https://google.com',
                'description' => 'cooooool',
                'likes_count' => 0,
                'comments_count' => 0,
                'views_count' => 0,
            ]],
            [[
                'name' => 'more realistic',
                'thumbnail' => 'https://lookslikeaurlbutisinvalidforsure.com',
                'description' => 'cooooool',
                'likes_count' => 0,
                'comments_count' => 0,
                'views_count' => 0,
            ]],
            [[
                'src' => 'dont know what other tests to write',
                'name' => 'dont know what other tests to write',
                'thumbnail' => 'dont know what other tests to write',
                'description' => 'dont know what other tests to write',
                'likes_count' => 'dont know what other tests to write',
                'comments_count' => 'dont know what other tests to write',
                'views_count' => 'dont know what other tests to write',
            ]],
        ];
    }
}