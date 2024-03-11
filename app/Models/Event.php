<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function dates()
    {
        return $this->hasMany(EventDate::class, "event_id", "id");
    }

    public function datesToUserTimezone()
    {
        $user_timezone = $this->get_user_timezone();

        // use carbon to convert from UTC to user timezone
        $new_dates = $this->dates->map(function($date) use ($user_timezone) {
            return  Carbon::parse($date->date)
                        ->setTimezone($user_timezone)
                        ->toDateTimeString();
        });

        return $new_dates;
    }

    public function get_user_timezone()
    {
        $user = User::where('id', $this->user_id)->first();
        return $user->timezone;
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
