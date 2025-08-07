<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'employee_id' => $data['employeeId'],
                'email' => $data['email'],
                'role' => 'employee',
                'password' => Hash::make($data['password']),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'role_id' => $data['roleId'],
                'department_id' => $data['departmentId'],
                'name' => $data['name'],
                'gender' => $data['gender'],
                'birth_date' => $data['birthDate'],
                'phone_number' => $data['phoneNumber'],
            ]);

            Schedule::create([
                'user_id' => $user->id,
                'shift_id' => $data['shiftId'],
                'location_id' => $data['locationId'],
            ]);

            return $user;
        });
    }

    public function login(array $data)
    {
        $user = User::where('employee_id', $data['employeeId'])->first();

        if (!$user) {
            throw new AuthenticationException(__('errorMessages.invalid_credentials'));
        }

        $user["employeeId"] = $user["employee_id"];

        unset($user["employee_id"]);

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException(__('errorMessages.invalid_credentials'));
        }

        if ($user->status == 0) {
            throw new AuthorizationException(__('errorMessages.account_disabled'));
        }

        $token = $user->createToken('login_token', [], now()->addHours(24))->plainTextToken;


        return [
            "token" => $token,
            "user" => $user
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return Helper::returnSuccess("Logout successful");
    }
}
