<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
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
        // email belongs to user 
        return [
            'email' => [
                'required',
                'email',
                'max:100',
                'exists:users,email'
            ],
            'code' => 'required|string'
         ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'The :attribute does not belong to any user.'
        ];
    }
}
