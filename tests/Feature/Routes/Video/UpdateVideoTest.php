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
}