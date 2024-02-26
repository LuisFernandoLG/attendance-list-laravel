<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function get_timezone_of_event()
    {
        $event = Event::where('id', $this->event_id)->first();
        $author = User::where('id', $event->user_id)->first();
        return $author->timezone;
    }

    public function attendance_url()
    {
        $url = route('attendance.store', ['event' => '1', 'shortId' =>'1']);
        return $url;
    }

}
