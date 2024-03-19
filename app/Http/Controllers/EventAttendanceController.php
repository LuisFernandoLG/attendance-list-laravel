<?php

namespace App\Http\Controllers;

use App\Models\ControlledListRecord;
use App\Models\Event;
use Illuminate\Http\Request;

class EventAttendanceController extends Controller
{
    public function show(Request $request, $id){

        $event = Event::where('id', $id)->where('user_id', request()->user()->id)->first();

        if(!$event){
            return response()->json([
                'message' => 'item not found'
            ], 404);
        }

        $query = ControlledListRecord::query();
        $pagination = $query->where('event_id', $id)->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'message' => 'item retrieved successfully',
            'pagination' => $pagination
        ]);
    }
}
