<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    use HasFactory;

    // hidden
    protected $hidden = ['created_at', 'updated_at', 'event_id', 'id'];
}
