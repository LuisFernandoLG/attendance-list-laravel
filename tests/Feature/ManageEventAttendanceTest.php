<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageEventAttendanceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    private function createEvent($user, $dates): int
    {
        Storage::fake('photos');
        $this->actingAs($user);
        $response = $this->post('/api/events', $this->eventData($dates), ['Accept' => 'application/json']);
        $response->assertStatus(201);
        $json = json_decode($response->getContent());
        return $json->item->id;
    }

    private function eventData($dates): array
    {
        return [
            'name' => 'Evento de prueba',
            'description' => 'Evento de prueba',
            'type' => 'CONTROLLED',
            'dates' => $dates,
            'image' => UploadedFile::fake()->image('avatar.jpg')
        ];
    }

    public function test_generated_code_is_equals_from_response_and_server_generated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $today = Carbon::today()->toDateTimeString();
        $eventId = $this->createEvent($user, [$today]);

        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'example@gmail.com'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);

        $json = json_decode($response->getContent());
        $attendance_code = $json->url_attendance;
        $generatedUrl = env('APP_URL').'/api/attendance/'.$eventId.'/'.$json->item->custom_id;
        $this->assertEquals($attendance_code, $generatedUrl);
    }
    
    public function test_any_person_register_assistance_by_auto_generated_code(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $today = Carbon::today()->toDateTimeString();
        $eventId = $this->createEvent($user, [$today]);

        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'example@gmail.com'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'item' => [
                'name',
                'custom_id',
                'event_id',
                'id'
            ],
            'url_attendance'
        ]);


        $json = json_decode($response->getContent());
        $customUrl = $json->url_attendance;
        
        $response = Http::get($customUrl, ['Accept' => 'application/json']);
        $code = $response->status();
        $this->assertEquals(201, $code);

    }

    public function test_users_cant_to_take_assistance_different_day(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        $eventId = $this->createEvent($user, [$tomorrow]);

        $response = $this->post('/api/events/'.$eventId.'/members',
        [
            'name' => 'John Doe',
            'email' => 'example@gmail.com',
            'custom_id' => '123456'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201);

        $json = json_decode($response->getContent());
        $customUrl = $json->url_attendance;

        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        $response = Http::get($customUrl, ['Accept' => 'application/json']);
        $code = $response->status();
        $this->assertEquals(404, $code);
    }

    public function test_attendance_code_is_linked_to_event_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $today = Carbon::today()->toDateTimeString();
        $eventId1 = $this->createEvent($user, [$today]);
        $eventId2 = $this->createEvent($user, [$today]);

        $response1 = $this->post('/api/events/'.$eventId1.'/members',
        [
            'name' => 'John Doe',
            'email' => 'example@gmail.como'
        ], ['Accept' => 'application/json']);

        $response1->assertStatus(201);
        $user1 = json_decode($response1->getContent());
        
        $response2 = $this->post('/api/events/'.$eventId2.'/members',
        [
            'name' => 'John Doe',
            'email' => 'example2@gmail.com'
        ], ['Accept' => 'application/json']);
        
        $response2->assertStatus(201);
        $user2 = json_decode($response2->getContent());
        
        $json = json_decode($response2->getContent());
        $link = env('APP_URL').'/attendance/'.$eventId2.'/'.$user1->item->custom_id;
        
        $response = Http::get($link, ['Accept' => 'application/json']);
        $code = $response->status();
        $this->assertEquals(404, $code);
    }


}
