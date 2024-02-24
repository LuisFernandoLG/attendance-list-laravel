<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Models\ControlledListRecord;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Member;
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
    public function store(StoreEventRequest $request)
    {
        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'user_id' => $request->user()->id
        ]);

        EventDate::insert(array_map(function($date) use ($event){
            return [
                'date' => $date,
                'event_id' => $event->id
            ];
        }, $request->dates));

        return response()->json([
            'message' => 'Event created successfully',
            'item' => $event->load('dates')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EventRequest $eventRequest, Event $event)
    {
        
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
