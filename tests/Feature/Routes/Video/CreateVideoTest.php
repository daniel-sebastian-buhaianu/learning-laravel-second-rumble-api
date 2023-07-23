<?php

namespace Tests\Feature\Routes\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateVideoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_create_a_video(): void
    {
        $attributes = [
            'url' => 'https://rumble.com/v2zndx2-andrew-tate-tucker-carlson-the-interview.html'
        ];

        $response = $this->post('api/videos', $attributes);

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function a_user_cannot_create_a_video(): void
    {
        $user = User::factory()->create();

        $attributes = [
            'url' => 'https://rumble.com/v2zndx2-andrew-tate-tucker-carlson-the-interview.html'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->post('/api/videos', $attributes);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function an_admin_can_create_a_video_with_valid_attributes(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->post('/api/channels', ['url' => 'https://rumble.com/c/tatespeech']);

        $attributes = [
            'url' => 'https://rumble.com/v2zndx2-andrew-tate-tucker-carlson-the-interview.html'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->post('/api/videos', $attributes);

        $response->assertCreated();
    }

    /**
     * @test
     */
    public function an_admin_cannot_create_a_video_with_valid_attributes_if_channel_doesnt_exist(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $attributes = [
            'url' => 'https://rumble.com/v2zndx2-andrew-tate-tucker-carlson-the-interview.html'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->post('/api/videos', $attributes);

        $response->assertStatus(422);
    }

    /**
     * @test
     * @dataProvider invalidAttributes
     */
    public function an_admin_cannot_create_a_video_with_invalid_attributes(array $invalidAttributes): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->post('/api/videos', $invalidAttributes);

        $response->assertRedirect();
    }

    public function invalidAttributes(): array
    {
        return [
            [[
                'url' => 'https://google.com'
            ]],
            [[
                'url' => 'https://rumble.com/v'
            ]],
            [[
                'url' => 'https://rumble.com/v123.html'
            ]],
            [[
                'url' => 'https://rumble.com/v1a3b4d-abc-defg.html'
            ]],
            [[
                'url' => 'https://rumble.com/v2zndx2123-andrew-tate-tucker-carlson-the-intervi123ew.html'
            ]],
            [[
                'url' => 'https://real-gibrish-url.net'
            ]]
        ];
    }


}