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
}