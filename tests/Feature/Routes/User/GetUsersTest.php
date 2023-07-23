<?php

namespace Tests\Feature\Routes\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_get_users(): void
    {
        User::factory(2)->create();
        
        $response = $this->get('api/users');

        $response->assertSee('Unauthorized');
    }

    /**
     * @test
     */
    public function a_user_cannot_get_users(): void
    {
        User::factory(3)->create();

        $user = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/users');

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function an_admin_can_get_users(): void
    {
        User::factory(3)->create();
        
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/users');

        $response->assertJsonCount(4, 'data');
    }
}