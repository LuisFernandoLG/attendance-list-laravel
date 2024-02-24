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
        return false;
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
            'image_url' => 'string',
            'type' => 'required|string|in:CONTROLLED,UNCONTROLLED',
            'dates' => 'required|array|min:1',
            'dates.*' => [
                'required',
                'date',
                'after_or_equal:today',
                'date_format:Y-m-d'
            ]
        ];
    }
}
