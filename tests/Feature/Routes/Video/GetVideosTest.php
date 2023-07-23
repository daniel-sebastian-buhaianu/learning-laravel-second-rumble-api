<?php

namespace Tests\Feature\Routes\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVideosTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_get_videos(): void
    {
        Video::factory(2)->create();
        
        $response = $this->get('api/videos');

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function a_user_can_get_videos(): void
    {
        Video::factory(2)->create();

        $user = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/videos');

        $response->assertSuccessful();
    }
}