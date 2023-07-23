<?php

namespace Tests\Feature\Routes\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserByIdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_get_a_user_by_id(): void
    {
        User::factory(2)->create();
        
        $response = $this->get('api/users/1');

        $response->assertSee('Unauthorized');
    }

    /**
     * @test
     */
    public function a_user_cannot_get_another_user_by_id(): void
    {
        User::factory(2)->create();

        $user = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/users/1');

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_user_can_get_themselves_by_id(): void
    {
        User::factory(2)->create();

        $user = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/users/' . $user->id);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function an_admin_can_get_any_user_by_id(): void
    {
        User::factory(2)->create();

        $user = User::factory()->create([
            'is_admin' => true
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->get('/api/users/1');

        $response->assertOk();
    }
}