<?php

namespace Tests\Feature\Routes\Channel;

use Tests\TestCase;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateChannelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_update_a_channel(): void
    {
        $channel = Channel::factory()->create();

        $attributes = [
            'name' => 'A channel name'
        ];
        
        $response = $this->patch('api/channels/' . $channel->id, $attributes);

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function a_user_cannot_update_a_channel(): void
    {
        $user = User::factory()->create();

        $channel = Channel::factory()->create();

        $attributes = [
            'name' => 'A channel name'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/channels/' . $channel->id, $attributes);
        
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function an_admin_can_update_a_channel(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $attributes = [
            'name' => 'A new channel name',
            'description' => 'Cool description',
            'banner' => 'https://img.freepik.com/free-vector/set-mixed-banners_53876-63010.jpg?w=2000',
            'avatar' => 'https://img.freepik.com/free-vector/set-mixed-banners_53876-63010.jpg?w=2000',
            'followers_count' => 1200,
            'videos_count' => 65
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/channels/' . $channel->id, $attributes);
        
        $response->assertJsonFragment($attributes);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_channel_id(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $attributes = [
            'id' => 'some id'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/channels/' . $channel->id, $attributes);
        
        $response->assertJsonFragment([
            'id' => $channel->id
        ]);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_channel_url(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $attributes = [
            'url' => 'https://google.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/channels/' . $channel->id, $attributes);
        
        $response->assertJsonFragment([
            'url' => $channel->url
        ]);
    }

    /**
     * @test
     */
    public function an_admin_cannot_update_a_channel_joining_date(): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $attributes = [
            'joined_at' => '2021-04-05'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/channels/' . $channel->id, $attributes);
        
        $response->assertJsonFragment([
            'joined_at' => $channel->joined_at
        ]);
    }

    /**
     * @test
     * @dataProvider invalidAttributes
     */
    public function an_admin_cannot_update_a_channel_with_invalid_attributes(array $invalidAttributes): void
    {
        $user = User::factory()->create([
            'is_admin' => true
        ]);

        $channel = Channel::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/channels/' . $channel->id, $invalidAttributes);
        
        $response->assertRedirect();
    }

    public function invalidAttributes(): array
    {
        return [
            [[   
                'name' => 'A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters A name thats longer than 255 characters ',
                'description' => 'A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters A description thats longer than 255 characters ',
                'banner' => 'this is not a url',
                'avatar' => 'this is not a url',
                'followers_count' => 'clearly not an integer',
                'videos_count' => 'clearly not an integer',
            ]],
            [[
                'name' => 'a valid name',
                'description' => 'a valid description',
                'banner' => 'invalid',
                'avatar' => 'invalid',
                'followers_count' => 1200,
                'videos_count' => 32
            ]],
            [[
                'name' => 'a valid name',
                'description' => 'a valid description',
                'banner' => 'https://google.com?q=valid url',
                'avatar' => 'https://google.com?q=valid url',
                'followers_count' => -1,
                'videos_count' => 20
            ]],
            [[
                'name' => 'a valid name',
                'description' => 'a valid description',
                'banner' => 'https://google.com?q=valid url',
                'avatar' => 'https://google.com?q=valid url',
                'followers_count' => 8000000001,
                'videos_count' => 20
            ]],
            [[
                'name' => 'a valid name',
                'description' => 'a valid description',
                'banner' => 'https://google.com?q=valid url',
                'avatar' => 'https://google.com?q=valid url',
                'followers_count' => 800000000,
                'videos_count' => 65536
            ]],
        ];
    }
}