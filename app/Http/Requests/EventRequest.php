<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        
        // validate if the user is owner of the event, user id is on the url
        $event_id = $this->route('id');
        $user_id = $this->user()->id;


        $is_owner = Event::where('id', $event_id)->where('user_id', $user_id)->exists();
        return $is_owner;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        ];
    }
}
