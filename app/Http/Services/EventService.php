<?php

namespace App\Http\Services;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\EventDate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

class EventService
{

    private $image_endpoint = 'https://api.imgbb.com/1/upload';

    public function get_all(Request $request): Collection
    {
        $events = Event::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();

        return $events;
    }

    public function storeImage($request) : string
    {
        try {

            $image = $request->file('image');

            $client = new Client();
            $response = $client->post($this->image_endpoint, [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($image->getPathname(), 'r'),
                        'filename' => $image->getClientOriginalName()
                    ],
                    [
                        'name' => 'key',
                        'contents' => env('IMGBB_API_KEY')
                    ]
                ]
            ]);

            $data = json_decode($response->getBody()->getContents());

            return $data->data->url;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function deleteAllDates($eventId): void
    {
        EventDate::where('event_id', $eventId)->delete();
    }

    public function insertDatesToEvent(Request $request, $event): SupportCollection
    {
        $user_timezone = $request->user()->timezone;

        EventDate::insert(array_map(function ($date) use ($event, $user_timezone) {
            // Convert date to utc removed
            // $utc_date = Carbon::createFromFormat('Y-m-d H:i:s', $date, $user_timezone)->setTimezone('UTC');

            return [
                'date' => $date,
                'event_id' => $event->id
            ];
        }, $request->dates));

        $dates = $event->datesToUserTimezone();
        return $dates;
    }

    public function registerEvent(StoreEventRequest $request): Event
    {

        $res = $request->hasFile('image') ? $this->storeImage($request) : null;
        $image_url = $res ? $res : 'https://picsum.photos/id/1/200/300';

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'image_url' => $image_url,
            'user_id' => $request->user()->id
        ]);

        
        // spread the dates to the the event object
        $dates = $this->insertDatesToEvent($request, $event);
        $event->local_dates = $dates;

        return $event;
    }

    public function updateEvent(Request $request, $id): Event
    {
        $event = Event::find($id);
        // $res = $this->storeImage($request);
        // $image_url = $res ? $res : $event->image_url;

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $this->deleteAllDates($event->id);
        $dates = $this->insertDatesToEvent($request, $event);
        $event->local_dates = $dates;

        return $event;
    }
}
