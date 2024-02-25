<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'type' => 'required|string|in:CONTROLLED,UNCONTROLLED',
            'dates' => 'required|array|min:1',
            'dates.*' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
            ]
        ];
    }
}
