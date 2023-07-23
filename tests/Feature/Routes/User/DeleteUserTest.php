<?php

namespace Tests\Feature\Routes\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_user(): void
    {
        User::factory(2)->create();
        
        $response = $this->delete('api/users/1');

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function a_user_can_delete_their_account(): void
    {
        User::factory(2)->create();

        $user = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':' . 'Abc123000!'),
        ])->delete('/api/users/' . $user->id);

        $response->assertSuccessful();
    }
}