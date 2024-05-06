<?php

namespace App\Http\Controllers;

use App\Models\ControlledListRecord;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventAttendanceController extends Controller
{

    private $membersPerPage = 30;

    public function show(Request $request, $id){

        // validate $date
        $fields = Validator::make($request->all(), [
            'date' => 'required|date|date_format:Y-m-d H:i:s',
        ]);

        if($fields->fails()){
            return response()->json([
                'message' => 'invalid date format'
            ], 400);
        }

        $event = Event::where('id', $id)->where('user_id', request()->user()->id)->first();
        $userTimezone = $request->user()->timezone;

        if(!$event){
            return response()->json([
                'message' => 'item not found'
            ], 404);
        }
        
       
        $date = Carbon::createFromDate($request->date);
        $datePlus24 = Carbon::createFromDate($request->date)->addHours(24);
        
        // $date = Carbon::createFromDate($request->date)->format('Y-m-d H:i:s');


        // return response()->json(([
        //     "startDate" => $date,
        //     "finalDate" => $datePlus24,
        // ]));
        

        config()->set('database.connections.mysql.strict', false);
        DB::reconnect();
        
        $query = ControlledListRecord::query()->with('member')->where('event_id', $id)->whereDate('created_at', $date);
        $query = ControlledListRecord::query()
                                        ->with('member')
                                        ->where('event_id', $id)
                                        ->where("created_at", ">", $date)
                                        ->where("created_at", "<", $datePlus24);



        if($request->has('search')){
            $query->whereHas('member', function($q) use ($request){
                $q->where('name', 'like', '%'.$request->search.'%');
            });
        }
        
        if($request->has('order')){
            if($request->order == 'asc'){
                $query->orderBy('created_at', 'asc');
            }
        }else{
            $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->has('perPage') ? $request->perPage : $this->membersPerPage;
        
        $pagination = $query->
                            select('*', DB::raw('MAX(created_at) as last_attendance, COUNT(*) as attendances'))
                                        ->groupBy('member_id')
                                        ->paginate($perPage);

        config()->set('database.connections.mysql.strict', true);
        DB::reconnect();

        return response()->json([
            'date' => $date,
            'userTimezone' => $userTimezone,
            'message' => 'item retrieved successfully',
            'pagination' => $pagination,
        ]);
    }
}
