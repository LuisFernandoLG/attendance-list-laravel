<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class attendanceController extends Controller
{
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
                'message' => 'User not foun'
            ], 404);
        }

        

        return response()->json([
            'message' => 'Attendance recorded'
        ]);
    }
}
