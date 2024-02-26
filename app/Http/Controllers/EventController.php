<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditEventRequest;
use App\Http\Requests\EventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Models\ControlledListRecord;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Member;
use Carbon\Carbon;
use EventService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, EventService $eventService)
    {
        $events = $eventService->get_all($request);

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


    public function store(StoreEventRequest $request, EventService $eventService)
    {
        $event  = $eventService->registerEvent($request);

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



    /**
     * Show the form for editing the specified resource.
     */



    public function edit(EditEventRequest $request, $event, EventService $eventService)
    {
        $event = $eventService->updateEvent($request, $event);

        return response()->json([
            'message' => 'item updated successfully',
            'item' => $event
        ]);
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

        if (!$event) {
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
