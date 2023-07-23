<?php

namespace Tests\Feature\Routes\Channel;

use Tests\TestCase;
use App\Models\User;
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

    /**
     * @test
     */
    public function a_user_can_get_channels(): void
    {
        Channel::factory(2)->create();

        $user = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/channels');

        $response->assertSuccessful();
    }
}