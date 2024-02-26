<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageEventMemberTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     private function createEvent($user): int
    {
        Storage::fake('photos');
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

    public function test_user_can_add_member(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventId = $this->createEvent($user);

        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'grave281@gmail.com',
            'phone' => '6242420721',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
    }

    public function test_user_can_remove_member(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventId = $this->createEvent($user);

        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'grave281@gmail.com',
            'phone' => '6242420721',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $json = json_decode($response->getContent());

        $response = $this->delete('/api/events/'.$eventId.'/members/'.$json->item->id, [], ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }

    public function test_user_can_get_members(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventId = $this->createEvent($user);
        // add a member
        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'user@gmail.com'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
        


        $response = $this->get('/api/events/'.$eventId.'/members', ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'custom_id',
                        'email',
                        'phone',
                        'details',
                        'image_url',
                        'notifyByEmail',
                        'notifyByPhone',
                        'created_at',
                        'updated_at',
                        'event_id',
                        'url_attendance'
                    ]
                ]
            ]
        ]);

    }

    public function test_user_can_update_member(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventId = $this->createEvent($user);
        // add a member
        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'example@gmail.com',
            'phone' => '6242420721',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $json = json_decode($response->getContent());

        $response = $this->put('/api/events/'.$eventId.'/members/'.$json->item->id,
        [
            'name' => 'John Doe 2',
            'phone' => '6242420721',
            'details' => 'Some details',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);

    }
}
