<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;




class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test a successful user registration.
     *
     * @return void
     */
    public function testSuccessfulRegistration()
    {
        $userData = [
            'name' => 'hamdaa',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User registered successfully.',
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'hamadaa',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test registration with missing required fields.
     *
     * @return void
     */
    public function testMissingRequiredFields()
    {
        $userData = [
            'username' => 'hmadaa',
            
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Test registration with invalid email address.
     *
     * @return void
     */
    public function testInvalidEmailAddress()
    {
        $userData = [
            'username' => 'hamadaa',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    // Additional test cases can be added based on different scenarios.
}

