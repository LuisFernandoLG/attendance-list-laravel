<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {

        $timezone = 'America/Mazatlan';
        $no_valid_timezone = 'not-valid-timezone';

        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => $no_valid_timezone,
        ], ['Accept' => 'application/json']);
        
        $response->assertStatus(422);

        $response2 = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => $timezone,
        ], ['Accept' => 'application/json']);

        $response2->assertStatus(201);
    }

    public function test_users_cant_register_with_same_email_again():void{
        $timezone = 'America/Mazatlan';

        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => $timezone,
        ]);

        
        $response2 = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => $timezone,
        ], ['Accept' => 'application/json']);


        $response2->assertStatus(422);
    }


}
