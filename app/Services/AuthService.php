<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function register(array $data)
    {
        $validator = Validator::make($data, [
            'employeeId' => 'required|string|unique:users,id',
            'employeeName' => 'required|string|max:255',
            'employeeGender' => 'required|string|in:male,female',
            'employeeBirthDate' => 'required|integer',
            'employeePhoneNumber' => 'required|string|max:15',
            'employeeEmail' => 'required|string|email|max:255|unique:users,email',
            'employeeRoleId' => 'required|integer',
            'employeeDepartmentId' => 'required|integer',
            'employeeShiftId' => 'required|integer',
            'employeeWorkLocationId' => 'required|integer',
            'employeePassword' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()
            ];
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'employee_id' => $data['employeeId'],
                'email' => $data['employeeEmail'],
                'role' => "employee",
                'password' => Hash::make($data['employeePassword']),
            ]);

            $userProfile = UserProfile::create([
                'user_id' => $user->id,
                'role_id' => $data['employeeRoleId'],
                'name' => $data['employeeName'],
                'department_id' => $data['employeeDepartmentId'],
                'gender' => $data['employeeGender'],
                'birth_date' => $data['employeeBirthDate'],
                'phone_number' => $data['employeePhoneNumber'],
            ]);

            $schedule = Schedule::create([
                'user_id' => $user->id,
                'shift_id' => $data['employeeShiftId'],
                'location_id' => $data['employeeWorkLocationId'],
            ]);

            DB::commit();
            return [
                'status' => true,
                'data' => [
                    'user' => $user,
                    'userProfile' => $userProfile,
                    'schedule' => $schedule
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'errors' => ["message" => $e->getMessage()]
            ];
        }
    }

    public function login(array $data)
    {
        $validator = Validator::make($data, [
            'employeeId' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "errors" => $validator->errors()
            ];
        }

        $user = User::where('employee_id', $data['employeeId'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return [
                "status" => false,
                "errors" => "UNAUTHORIZED"
            ];
        }

        $token = $user->createToken('login_token')->plainTextToken;

        return [
            "status" => true,
            "data" => [
                "token" => $token,
                "user" => $user
            ]
        ];
    }
}
