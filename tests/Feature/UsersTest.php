<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_view_all_users(): void
    {
        User::factory(2)->create();
        
        $response = $this->get('api/users');

        $response->assertSee('Unauthorized');
    }

    /**
     * @test
     */
    public function a_user_cannot_view_all_users(): void
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
    public function an_admin_can_view_all_users(): void
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

    /**
     * @test
     */
    public function a_guest_cannot_view_a_user(): void
    {
        User::factory(2)->create();
        
        $response = $this->get('api/users/1');

        $response->assertSee('Unauthorized');
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_user(): void
    {
        User::factory(2)->create();
        
        $response = $this->patch('api/users/1');

        $response->assertSee('Unauthorized');
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_user(): void
    {
        User::factory(2)->create();
        
        $response = $this->delete('api/users/1');

        $response->assertSee('Unauthorized');
    }
}
