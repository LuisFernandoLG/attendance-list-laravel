<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\StoreEventMemberRequest;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Sqids\Sqids;

class EventMemberController extends Controller
{
    public function index(EventRequest $eventRequest, $id){
        $event = Event::where('id', $id)->where('user_id', request()->user()->id)->first();

        $query = Member::query()->where('event_id', $event->id)->orderBy('created_at', 'desc');

        $memberListPagination = $query->paginate(10);

        $memberListPagination->getCollection()->transform(function ($member) use ($event) {
            $member->url_attendance = route('attendance.store', ['event' => $event->id, 'shortId' => $member->custom_id]);
            return $member;
        });

        return response()->json([
            'message' => 'Pagination retrieved successfully',
            'pagination' => $memberListPagination
        ]);
    }

    public function store(StoreEventMemberRequest $request, $eventId){
        // get the count members of the event
        $membersCount = Member::where('event_id', $eventId)->count() + 1;
        $randomNumber = rand(100, 999);

        $shortHumanId = $this->getRandomHumanId($eventId, $membersCount, $randomNumber);

        $member = Member::create([
            'name' => $request->name,
            // extract first three letters of the name and append
            'custom_id' => $shortHumanId ,
            'email' => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'details' => $request->details,
            // 'image_url' => $request->image_url,
            'notifyByEmail' => $request->notifyByEmail ? true : false,
            // 'notifyByPhone' => $request->notifyByPhone,
            'event_id' => $eventId,
        ]);

        return response()->json([
            'message' => 'Member created successfully',
            'item' => $member,
            'url_attendance' =>  route('attendance.store', ['event' => $eventId, 'shortId' => $shortHumanId])
        ], Response::HTTP_CREATED);

    }

    public function destroy(Request $request, Event $event, Member $member){
        $member->delete();

        return response()->json([
            'message' => 'Member deleted successfully',
            'item' => $member,
            'event' => $event
        ]);
    }

    public function edit(Request $request, Event $event, Member $member){
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'phone' => 'string|max:255',
            'details' => 'string|max:255',
            'image_url' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $member->update([
            'name' => $request->name ?? $member->name,
            'email' => $request->email ?? $member->email,
            'details' => $request->details ?? $member->details,
            'phone' => $request->phone ?? $member->phone,
            'image_url' => $request->image_url ?? $member->image_url,
        ]);

        return response()->json([
            'message' => 'Member updated successfully',
            'item' => $member,
            'event' => $event
        ]);
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
}
