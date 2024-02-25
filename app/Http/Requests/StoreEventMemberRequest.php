<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // validate if the user is owner of the event, user id is on the url
        $id = $this->route('id');
        $user_id = $this->user()->id;

        $is_owner = Event::where('id', $id)->where('user_id', $user_id)->exists();
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'email',
            'phone' => 'string',
            'details' => 'string',
            'image_url' => 'string',
            'notifyByEmail' => 'boolean',
            'notifyByPhone' => 'boolean',
        ];
    }
}
