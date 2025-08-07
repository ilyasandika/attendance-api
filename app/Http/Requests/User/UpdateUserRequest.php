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
            'whatsapp' => 'nullable|string|min:10|max:20',
            'linkedin' => 'nullable|url',
            'telegram' => 'nullable|string|min:10|max:20',
            'biography' => 'nullable|string',
            'status' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

//    public function messages(): array
//    {
//        return [
//            'id.required' => __('userValidation.id_required'),
//            'id.integer' => __('userValidation.id_integer'),
//
//            'employeeId.required' => __('userValidation.employee_id_required'),
//            'employeeId.string' => __('userValidation.employee_id_string'),
//            'employeeId.unique' => __('userValidation.employee_id_unique'),
//
//            'name.required' => __('userValidation.name_required'),
//            'name.string' => __('userValidation.name_string'),
//            'name.max' => __('userValidation.name_max'),
//
//            'gender.required' => __('userValidation.gender_required'),
//            'gender.string' => __('userValidation.gender_string'),
//            'gender.in' => __('userValidation.gender_in'),
//
//            'birthDate.required' => __('userValidation.birth_date_required'),
//            'birthDate.integer' => __('userValidation.birth_date_integer'),
//
//            'phoneNumber.required' => __('userValidation.phone_number_required'),
//            'phoneNumber.string' => __('userValidation.phone_number_string'),
//            'phoneNumber.max' => __('userValidation.phone_number_max'),
//
//            'email.required' => __('userValidation.email_required'),
//            'email.string' => __('userValidation.email_string'),
//            'email.email' => __('userValidation.email_email'),
//            'email.max' => __('userValidation.email_max'),
//            'email.unique' => __('userValidation.email_unique'),
//
//            'roleId.required' => __('userValidation.role_id_required'),
//            'roleId.integer' => __('userValidation.role_id_integer'),
//
//            'departmentId.required' => __('userValidation.department_id_required'),
//            'departmentId.integer' => __('userValidation.department_id_integer'),
//
//            'shiftId.required' => __('userValidation.shift_id_required'),
//            'shiftId.integer' => __('userValidation.shift_id_integer'),
//
//            'locationId.required' => __('userValidation.location_id_required'),
//            'locationId.integer' => __('userValidation.location_id_integer'),
//
//            'password.string' => __('userValidation.password_string'),
//            'password.min' => __('userValidation.password_min'),
//
//            'whatsapp.string' => __('userValidation.whatsapp_string'),
//            'whatsapp.min' => __('userValidation.whatsapp_min'),
//            'whatsapp.max' => __('userValidation.whatsapp_max'),
//
//            'linkedin.url' => __('userValidation.linkedin_url'),
//
//            'telegram.string' => __('userValidation.telegram_string'),
//            'telegram.min' => __('userValidation.telegram_min'),
//            'telegram.max' => __('userValidation.telegram_max'),
//
//            'biography.string' => __('userValidation.biography_string'),
//
//            'status.required' => __('userValidation.status_required'),
//            'status.boolean' => __('userValidation.status_boolean'),
//
//            'photo.image' => __('userValidation.photo_image'),
//            'photo.mimes' => __('userValidation.photo_mimes'),
//            'photo.max' => __('userValidation.photo_max'),
//        ];
//    }
}
