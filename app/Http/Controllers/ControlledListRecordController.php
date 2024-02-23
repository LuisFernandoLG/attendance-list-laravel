<?php

namespace App\Http\Controllers;

use App\Models\ControlledListRecord;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Member;
use Illuminate\Http\Request;

class ControlledListRecordController extends Controller
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
    public function store(Request $request, $eventId, $shortId)
    {

        // validate if event exists
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        // validate if the user is owner of the event
        $user = Member::where('event_id', $eventId)
            ->where('custom_id', $shortId)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // validate dates

        // validate if the array of dates is any of them today
        $dates = EventDate::where('event_id', $eventId)->get();

        $today = date('Y-m-d');

        $dateValid = false;

        foreach ($dates as $date) {
            if ($date->date == $today) {
                $dateValid = true;
                break;
            }
        }

        if (!$dateValid) {
            return response()->json([
                'message' => 'Event not today'
            ], 404);
        }

        ControlledListRecord::create([
            'event_id' => $eventId,
            'member_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Attendance recorded'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ControlledListRecord $controlledListRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ControlledListRecord $controlledListRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ControlledListRecord $controlledListRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ControlledListRecord $controlledListRecord)
    {
        //
    }
}
