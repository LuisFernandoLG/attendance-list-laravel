<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // validate if the user is owner of the event
        return $this->event->user_id === $this->user()->id;
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
            'event_id' => 'required|integer|exists:events,id',
        ];
    }
}
