<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function get()
    {

        $user = User::with('profile.role', 'profile.department')->get()->map(function ($user) {
            return [
                "employeeId" => $user->employee_id,
                "employeeName" => $user->profile->name,
                "employeeEmail" => $user->profile->email,
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
}
