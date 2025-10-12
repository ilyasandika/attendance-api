<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'employeeId' => 'required|string|unique:users,employee_id|regex:/^[a-zA-Z0-9]+$/',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'birthDate' => 'required|integer',
            'roleAccount' => 'nullable|in:admin,employee',
            'phoneNumber' => 'required|string|regex:/^\+?[0-9]{9,20}$/',
            'email' => 'required|string|email|max:255|unique:users,email',
            'roleId' => 'required|integer',
            'departmentId' => 'required|integer',
            'shiftId' => 'required|integer',
            'locationId' => 'required|integer',
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|string|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'phoneNumber.regex' => __('validation.phoneNumber.regex'),
            'employeeId.regex' => __('validation.employeeId.regex'),
        ];
    }

}
