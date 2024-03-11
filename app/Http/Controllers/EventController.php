<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditEventRequest;
use App\Http\Requests\EventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Services\EventService;
use App\Models\ControlledListRecord;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Member;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(EventRequest $eventRequest, $id)
    {
        $event = Event::find($id);
        $event->load('dates');        

        return response()->json([
            'message' => 'item retrieved successfully',
            'item' => $event
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */



    //  TODO : This function not working
    public function edit(EditEventRequest $request, $id, EventService $eventService)
    {
        $event = $eventService->updateEvent($request, $id);

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
