<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sqids\Sqids;


class MemberController extends Controller
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'email',
            'phone' => 'string',
            'details' => 'string',
            'image_url' => 'string',
            'notifyByEmail' => 'boolean',
            'notifyByPhone' => 'boolean',
            'event_id' => 'required|integer',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }
        
        // validate if event exists
        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        // validate if the user is owner of the event
        if ($event->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

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

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    // recursive function to generate a random human id
    private function getRandomHumanId($eventId, $membersCount, $randomNumber){
        $sqids = new Sqids();
        $id = $sqids->encode([$eventId, $membersCount, $randomNumber]);

        // check if the id exists
        $member = Member::where('custom_id', $id)->first();
        if ($member) {
            $randomNumber = rand(1000, 9999);
            return $this->getRandomHumanId($eventId, $membersCount, $randomNumber);
        }

        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
