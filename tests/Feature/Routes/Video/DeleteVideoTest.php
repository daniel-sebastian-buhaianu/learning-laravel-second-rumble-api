<?php

namespace Tests\Feature\Routes\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteVideoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_video(): void
    {
        $video = Video::factory()->create();

        $response = $this->delete('api/videos/' . $video->id);

        $response->assertUnauthorized();
    }
}