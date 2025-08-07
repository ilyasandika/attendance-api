<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            "latitude" => "required|numeric",
            "longitude" => "required|numeric",
            "radius" => "required|integer",
            "address" => "required|string",
            "description" => "required|string",
            "default" => "required|boolean"
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
