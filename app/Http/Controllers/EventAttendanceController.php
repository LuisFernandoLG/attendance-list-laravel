<?php

namespace App\Http\Controllers;

use App\Models\ControlledListRecord;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventAttendanceController extends Controller
{

    private $membersPerPage = 30;

    public function show(Request $request, $id){

        $event = Event::where('id', $id)->where('user_id', request()->user()->id)->first();
        $userTimezone = $request->user()->timezone;

        if(!$event){
            return response()->json([
                'message' => 'item not found'
            ], 404);
        }

        $now = Carbon::now()->timezone($userTimezone)->format('Y-m-d');
        $date = $request->has('date') ? Carbon::createFromDate($request->date)->format('Y-m-d') : $now;

        config()->set('database.connections.mysql.strict', false);
        DB::reconnect();
        
        $query = ControlledListRecord::query()->with('member')->where('event_id', $id)->whereDate('created_at', $date);


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
