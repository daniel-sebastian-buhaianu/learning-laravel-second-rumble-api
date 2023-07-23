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
        User::factory(10)->create();
        
        $response = $this->get('api/users');

        $response->assertSee('Unauthorized');
    }
}
