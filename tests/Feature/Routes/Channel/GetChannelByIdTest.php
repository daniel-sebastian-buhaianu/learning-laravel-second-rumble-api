<?php

namespace Tests\Feature\Routes\Channel;

use Tests\TestCase;
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
}