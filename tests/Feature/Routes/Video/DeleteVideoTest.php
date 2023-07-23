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

    /**
     * @test
     */
    public function a_user_cannot_delete_a_video(): void
    {
        $user = User::factory()->create();

        $video = Video::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->delete('/api/videos/' . $video->id);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function an_admin_can_delete_a_video(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $video = Video::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->delete('/api/videos/' . $video->id);

        $response->assertSuccessful();
    }
}