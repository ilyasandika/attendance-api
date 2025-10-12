<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id') ?? (int)Auth::user()->id;

        return [
            'id' => 'required|integer',
            'employeeId' => [
                'required',
                'string',
                Rule::unique('users', 'employee_id')->ignore($userId),
            ],
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'birthDate' => 'required|integer',
            'phoneNumber' => 'required|string|max:15',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'roleId' => 'required|integer',
            'departmentId' => 'required|integer',
            'shiftId' => 'required|integer',
            'locationId' => 'required|integer',
            'password' => 'nullable|string|min:6',
            'whatsapp' => 'nullable|string|min:8|max:20',
            'linkedin' => 'nullable|url',
            'telegram' => 'nullable|string|min:8|max:20',
            'biography' => 'nullable|string',
            'status' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
