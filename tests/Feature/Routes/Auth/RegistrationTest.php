<?php

namespace Tests\Feature\Routes\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_can_register_with_valid_credentials(): void
    {
        $attributes = [
            'email' => 'test@test.com',
            'password' => 'Abc123000!'
        ];

        $this->post('api/register', $attributes);

        $this->assertDatabaseHas('users', ['email' => $attributes['email']]);
    }

    /** @test */
    public function guest_cannot_register_with_invalid_email(): void
    {
        $attributes = [
            'email' => 'test.com',
            'password' => 'Abc123000!'
        ];

        $this->post('api/register', $attributes);

        $this->assertDatabaseMissing('users', ['email' => $attributes['email']]);
    }

    /** @test */
    public function guest_cannot_register_with_weak_password(): void
    {
        $attributes = [
            'email' => 'test@test.com',
            'password' => 'Abc123000'
        ];

        $this->post('api/register', $attributes);

        $this->assertDatabaseMissing('users', ['email' => $attributes['email']]);
    }
}
