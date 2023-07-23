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

    /**
     * @test
     */
    public function a_user_cannot_delete_a_channel(): void
    {
        $user = User::factory()->create();

        $channel = Channel::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->delete('/api/channels/' . $channel->id);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function an_admin_can_delete_a_channel(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->delete('/api/channels/' . $channel->id);

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function an_admin_cannot_delete_a_channel_if_it_doesnt_exist(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->delete('/api/channels/' . $channel->id . '123');

        $response->assertForbidden();
    }
}