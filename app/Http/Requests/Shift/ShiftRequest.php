<?php

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
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
            "name" => "required|string",
            "description" => "required|string",
            'default' => 'required|boolean',
            "monday" => "required|array",
            "tuesday" => "required|array",
            "wednesday" => "required|array",
            "thursday" => "required|array",
            "friday" => "required|array",
            "saturday" => "required|array",
            "sunday" => "required|array",
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
