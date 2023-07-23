<?php

namespace Tests\Feature\Routes\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
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
}