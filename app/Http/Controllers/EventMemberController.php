<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\StoreEventMemberRequest;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventMemberController extends Controller
{
    public function index(EventRequest $eventRequest, Event $event){
        $event->load('dates');

        return response()->json([
            'message' => 'item retrieved successfully',
            'item' => $event
        ]);
    }

    public function store(StoreEventMemberRequest $request){
        // get the count members of the event
        $membersCount = Member::where('event_id', $request->event_id)->count() + 1;
        $eventId = $request->event_id;
        $randomNumber = rand(100, 999);

        $shortHumanId = $this->getRandomHumanId($eventId, $membersCount, $randomNumber);

        $member = Member::create([
            'name' => $request->name,
            // extract first three letters of the name and append
            'custom_id' => $shortHumanId,
            // 'email' => $request->email,
            // 'phone' => $request->phone,
            // 'details' => $request->details,
            // 'image_url' => $request->image_url,
            // 'notifyByEmail' => $request->notifyByEmail,
            // 'notifyByPhone' => $request->notifyByPhone,
            'event_id' => $request->event_id,
        ]);

        return response()->json([
            'message' => 'Member created successfully',
            'item' => $member
        ]);

    }
}
