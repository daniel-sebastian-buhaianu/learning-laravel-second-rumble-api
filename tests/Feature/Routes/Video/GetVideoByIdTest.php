<?php

namespace Tests\Feature\Routes\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVideoByIdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_get_a_video_by_id(): void
    {
        $video = Video::factory()->create();
        
        $response = $this->get('api/videos/' . $video->id);

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function a_user_can_get_a_video_by_id(): void
    {
        $user = User::factory()->create();
        
        $video = Video::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/videos/' . $video->id);

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function a_user_cannot_get_a_video_by_id_if_it_doesnt_exist(): void
    {
        $user = User::factory()->create();
        
        $video = Video::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/videos/' . $video->id . '123');

        $response->assertNotFound();
    }
}