<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class EditEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpg,png,jpeg,webp|max:2048',
            'dates' => 'required|array|min:1',
            'dates.*' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
            ]
        ];
    }
}
