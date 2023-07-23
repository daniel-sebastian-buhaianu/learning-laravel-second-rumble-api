<?php

namespace Tests\Feature\Routes\Channel;

use Tests\TestCase;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteChannelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_channel(): void
    {
        $channel = Channel::factory()->create();

        $response = $this->delete('api/channels/' . $channel->id);

        $response->assertUnauthorized();
    }
}