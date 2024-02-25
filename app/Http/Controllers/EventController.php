<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Models\ControlledListRecord;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Member;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where('user_id', request()->user()->id)->get();

        return response()->json([
            'message' => 'items retrieved successfully',
            'items' => $events
        ]);
    }

    // public function p

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    private function storeImage($request)
    {
        try {
            
            $image = $request->file('image');

            $client = new Client();
            $response = $client->post('https://api.imgbb.com/1/upload', [
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

            $json = json_decode($response->getBody()->getContents(), true);
            $data = $json['data'];
            return $data['url'];
        } catch (\Throwable $th) {
            return false;
        }

    }

    public function store(StoreEventRequest $request)
    {

        $res = $this->storeImage($request);
        $image_url = $res ? $res : 'https://picsum.photos/id/1/200/300';
    
        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'image_url' => $image_url,
            'user_id' => $request->user()->id
        ]);

        $user_timezone = $request->user()->timezone;

        EventDate::insert(array_map(function($date) use ($event, $user_timezone) {

            $utc_date = Carbon::createFromFormat('Y-m-d H:i:s', $date, $user_timezone)->setTimezone('UTC');

            return [
                'date' => $utc_date,
                'event_id' => $event->id
            ];
        }, $request->dates));

        $dates = $event->datesToUserTimezone();
        // spread the dates to the the event object
        $event->local_dates = $dates;

        return response()->json([
            'message' => 'Event created successfully',
            'item' => $event,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EventRequest $eventRequest, Event $event)
    {
        $event->load('dates');

        return response()->json([
            'message' => 'item retrieved successfully',
            'item' => $event
        ]);
    }

    
    public function showWithMembers(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('user_id', request()->user()->id)->first();

        if(!$event){
            return response()->json([
                'message' => 'item not found'
            ], 404);
        }

        $memberListPagination = Member::where('event_id', $event->id)->paginate(10);

        return response()->json([
            'message' => 'item retrieved successfully',
            'pagination' => $memberListPagination
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function showWithAttendance()
    {
        
    }


    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $event = Event::where('id', $request->id)->where('user_id', $request->user()->id)->first();

        if(!$event){
            return response()->json([
                'message' => 'item not found'
            ], 404);
        }

        $event->delete();

        return response()->json([
            'message' => 'item deleted successfully'
        ]);
    }

}
