<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageEventTest extends TestCase
{
    use RefreshDatabase;

    private function createEvent(): int
    {
        Storage::fake('photos');
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post('/api/events', $this->eventData(), ['Accept' => 'application/json']);
        $response->assertStatus(201);
        $json = json_decode($response->getContent());
        return $json->item->id;
    }

    private function eventData(): array
    {
        return [
            'name' => 'Evento de prueba',
            'description' => 'Evento de prueba',
            'type' => 'CONTROLLED',
            'dates' => ['2024-12-12 00:00:00'],
            'image' => UploadedFile::fake()->image('avatar.jpg')
        ];
    }

    public function test_user_can_delete_event(): void
    {
        $eventId = $this->createEvent();
        $response = $this->delete('/api/events/'.$eventId, [], ['Accept' => 'application/json']);
        $response->assertStatus(200);
    }

    public function test_user_can_update_event(): void
    {
        $eventId = $this->createEvent();
        $response = $this->put('/api/events/'.$eventId, $this->eventData(), ['Accept' => 'application/json']);
        $response->assertStatus(200);
    }

    public function test_user_can_get_event(): void
    {
        $eventId = $this->createEvent();
        $response = $this->get('/api/events/'.$eventId, [], ['Accept' => 'application/json']);
        $response->assertStatus(200);
    }

    public function test_user_can_get_all_events(): void
    {
        $this->createEvent();
        $response = $this->get('/api/events', [], ['Accept' => 'application/json']);
        $response->assertStatus(200);
    }

    public function test_user_cant_access_to_events_without_authentication(): void
    {
        $response = $this->get('/api/events', ['Accept' => 'application/json', 'Content-Type' => 'application/json']);
        // show body
        $response->assertStatus(401);
    }

    public function test_user_cant_access_to_event_without_authentication(): void
    {
        $response = $this->get('/api/events/1', ['Accept' => 'application/json', 'Content-Type' => 'application/json']);
        $response->assertStatus(401);
    }

    public function test_user_cant_access_to_event_which_is_not_owned_by_him(): void
    {

        $eventId = $this->createEvent();
        
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/events/'.$eventId, [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
        

    }
}
