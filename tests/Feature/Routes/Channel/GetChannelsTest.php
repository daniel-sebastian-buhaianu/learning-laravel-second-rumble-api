<?php

namespace Tests\Feature\Routes\Channel;

use Tests\TestCase;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetChannelsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_get_channels(): void
    {
        Channel::factory(2)->create();
        
        $response = $this->get('api/channels');

        $response->assertUnauthorized();
    }
}