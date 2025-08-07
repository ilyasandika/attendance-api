<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($attendance) {
                return [
                    'attendanceId'            => $attendance->id,
                    'userId'                  => $attendance->user_id,
                    'employeeId'              => $attendance->user->employee_id ?? null,
                    'employeeName'            => $attendance->user->profile->name ?? null,
                    'employeeRole'            => $attendance->user->profile->role->name ?? null,
                    'employeeDepartment'      => $attendance->user->profile->department->name ?? null,
                    'date'                    => $attendance->date,
                    'checkInTime'             => $attendance->check_in_time,
                    'checkOutTime'            => $attendance->check_out_time,
                    'checkInPhoto'            => $attendance->check_in_photo,
                    'checkOutPhoto'           => $attendance->check_out_photo,
                    'checkInLatitude'         => $attendance->check_in_latitude,
                    'checkInLongitude'        => $attendance->check_in_longitude,
                    'checkOutLatitude'        => $attendance->check_out_latitude,
                    'checkOutLongitude'       => $attendance->check_out_longitude,
                    'scheduleId'              => $attendance->schedule_id,
                    'checkInStatus'           => $attendance->check_in_status,
                    'checkOutStatus'          => $attendance->check_out_status,
                    'checkInOutsideLocation'  => (bool) $attendance->check_in_outside_location,
                    'checkOutOutsideLocation' => (bool) $attendance->check_out_outside_location,
                    'checkInAddress'          => $attendance->check_in_address,
                    'checkOutAddress'         => $attendance->check_out_address,
                    'checkInComment'          => $attendance->check_in_comment,
                    'checkOutComment'         => $attendance->check_out_comment,
                    'autoCheckOut'            => $attendance->auto_checkout,
                ];
            }),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'lastPage'    => $this->lastPage(),
                'total'       => $this->total(),
            ],
        ];
    }
}
