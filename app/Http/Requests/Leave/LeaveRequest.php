<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
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
        $rules = [
            'type' => ['required', 'string'],
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
            'reason' => ['required', 'string'],
        ];

        if ($this->route('id')) {
            // update
            $rules['attachment'] = ['nullable', 'file', 'mimes:pdf', 'max:2048'];
        } else {
            // create
            $rules['attachment'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        return $rules;
    }
}
