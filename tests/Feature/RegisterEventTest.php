<?php

namespace Tests\Feature;

use App\Models\User;
use Ichtrojan\Otp\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterEventTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_user_cant_register_event_without_authentication(): void
    {
        Storage::fake('photos');
        $response = $this->post('/api/events', 
        [
            'name' => 'Evento de prueba',
            'description' => 'Evento de prueba',
            'type' => 'CONTROLLED',
            'dates' => ['2024-12-12 00:00:00'],
            'image' => UploadedFile::fake()->image('avatar.jpg')
        ], [
            'Accept' => 'application/json',
        
        ]);

        $response->assertStatus(401);
    }


    public function test_register_event(): void
    {
        Storage::fake('photos');
        $user = User::factory()->create();

        // Authenticate as the fake user
        $this->actingAs($user);

        // register evento form part. image should be a file
        $response = $this->post('/api/events', 
        [
            'name' => 'Evento de prueba',
            'description' => 'Evento de prueba',
            'type' => 'CONTROLLED',
            'dates' => ['2024-12-12 00:00:00'],
            'image' => UploadedFile::fake()->image('avatar.jpg')
        ],  [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(201);
    }



}
