<?php

namespace Tests\Feature\Routes\Channel;

use Tests\TestCase;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetChannelByIdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_get_a_channel_by_id(): void
    {
        $channel = Channel::factory()->create();
        
        $response = $this->get('api/channels/' . $channel->id);

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function a_user_can_get_a_channel_by_id(): void
    {
        $user = User::factory()->create();
        
        $channel = Channel::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/channels/' . $channel->id);

        $response->assertSuccessful();
    }
}