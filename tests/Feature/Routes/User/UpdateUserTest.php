<?php

namespace Tests\Feature\Routes\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

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
    public function a_user_cannot_update_another_user(): void
    {
        User::factory(2)->create();

        $user = User::factory()->create();

        $attributes = [
            'email' => 'some.dork@gmail.com',
            'is_admin' => true
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->patch('/api/users/1', $attributes);

        $response->assertStatus(403);
    }    
}