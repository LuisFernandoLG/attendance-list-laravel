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
