<?php

namespace App\Http\Controllers;

use App\Models\ControlledListRecord;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        
        // validate if the user is owner of the event
        $user = Member::where('event_id', $eventId)
        ->where('custom_id', $shortId)
        ->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        
        $timezone = $request->timezone ? $request->timezone : $user->get_timezone_of_event();
        // validate dates

        // validate if the array of dates is any of them today
        $dates = EventDate::where('event_id', $eventId)->get();

        $dateValid = false;

        foreach ($dates as $item) {
            // For some reason the date is one day ahead
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $item->date); // ✅
            $now = Carbon::now(); // ✅
            $datePlus24 = Carbon::createFromFormat('Y-m-d H:i:s', $item->date)->addDay(2); // ✅

            $isValid = $now->greaterThanOrEqualTo($date) && $now->lessThanOrEqualTo($datePlus24 );
                // $data = [
                    
                //     "item" => $item->date,
                //     "date" => $date->toString(),
                //     "today" => $now->toString(),
                //     "datePlus24" => $datePlus24->toString(),
                //     "datePlus24 timezone" => $datePlus24 ->tzName,
                //     "isValid" => $isValid,
                //     "greather than" => $now->gte($date),
                //     "today timezone" => $now->tzName,
                //     "less than" => $now->lessThanOrEqualTo($datePlus24 )
                // ];

                // return response()->json([
                //     'data' => $data
                // ], 202);

            if ($isValid) {
                $dateValid = true;
                break;
            }
        }
        
        if (!$dateValid) {
            return response()->json([
                'message' => 'Event not today',
                'expected_dates' => $dates,
            ], 404);
        }


        $attendance = ControlledListRecord::create([
            'event_id' => $eventId,
            'member_id' => $user->id,
        ]);


        // $attendance_local_time = Carbon::parse($attendance->created_at)->setTimezone($timezone)->toDateTimeString();

        return response()->json([
            'message' => 'Attendance recorded',
            // 'attendance_at' => $attendance_local_time,
            'timezone' => $timezone,
            'attendance' => $attendance
        ], Response::HTTP_CREATED);
    }

    public function getInfo(Request $request, $eventId, $shortId){
        $user = Member::where('event_id', $eventId)
        ->where('custom_id', $shortId)
        ->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

       $event = Event::find($eventId);
       $dates = EventDate::where('event_id', $eventId)->get();
    
       return response()->json([
            'message'=> 'item data retrieved successfully',
           'event' => $event,
           'user' => $user,
           'dates'=> $dates
       ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(ControlledListRecord $controlledListRecord)
    {
        // displays event and user data

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
