<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Schedule;

class ScheduleService
{
    public function findAllSchedules()
    {
        $schedule = Schedule::with('shift', 'location', 'user.profile', 'user.profile.department', 'user.profile.role')->get()->map(function ($schedule) {
            return [
                "schedulesId" => $schedule->id,
                "employeeId" => $schedule->user->employee_id,
                "employeeName" => $schedule->user->profile->name,
                "employeeRole" => $schedule->user->profile->role->name,
                "employeeDepartment" => $schedule->user->profile->department->name,
                "employeeShift" => $schedule->shift->name,
                "employeeWorkLocation" => $schedule->location->name
            ];
        });

        return ($schedule) ?  Helper::returnSuccess($schedule) : Helper::returnIfNotFound($schedule, "User not found");
    }
}
