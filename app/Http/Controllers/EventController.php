<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

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
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'image_url' => 'string',
            'type' => 'required|string|in:CONTROLLED,UNCONTROLLED',
            'dates' => 'required|array|min:1',
            'dates.*' => [
                'required',
                'date',
                'after_or_equal:today',
                'date_format:Y-m-d'
            ]
        ]);  

        if($validation->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 400);
        }

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
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
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
    public function destroy(Event $event)
    {
        //
    }
}
