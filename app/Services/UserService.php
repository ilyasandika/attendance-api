<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function findAll()
    {

        $user = User::with('profile.role', 'profile.department')->get()->map(function ($user) {
            return [
                "id" => $user->id,
                "profile_id" => $user->profile->id,
                "employeeId" => $user->employee_id,
                "employeeEmail" => $user->email,
                "employeeName" => $user->profile->name,
                "employeeRole" => $user->profile->role->name,
                "employeeDepartment" => $user->profile->department->name,
                "dateCreated" => strtotime($user->created_at),
                "accountStatus" => $user->status,
            ];
        });

        if ($user) {
            return [
                'status' => true,
                'data' => $user
            ];
        }

        return [
            'status' => false,
            'errors' => ['message' => "user not found"]
        ];
    }

    public function findById(int $id)
    {
        try {
            $user = User::with('schedule.shift', 'schedule.location', 'profile.role', 'profile.department')->findOrFail($id);
            $data = [
                "id" => $user->id,
                "employeeId" => $user->employee_id,
                "employeeEmail" => $user->email,
                "employeeName" => $user->profile->name,
                "employeeGender" => $user->profile->gender,
                "employeeBirthDate" => strtotime($user->profile->birth_date),
                "employeePhoneNumber" => $user->profile->phone_number,
                "employeeRole" => $user->profile->role->name,
                "employeeDepartment" => $user->profile->department->name,
                "employeeShift" => $user->schedule->shift->name,
                "employeeWorkLocation" => $user->schedule->location->name,
                "employeeWhatsApp" => $user->profile->whatsapp,
                "employeeLinkedin" => $user->profile->linkedin,
                "employeeTelegram" => $user->profile->telegram,
                "employeeBiography" => $user->profile->Biography,
                "accountStatus" => $user->status,
            ];

            return [
                'status' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'errors' => ['message' => "user not found"]
            ];
        }
    }
}
