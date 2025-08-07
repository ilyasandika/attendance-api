<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'attendanceId'            => $this->id,
            'userId'                  => $this->user_id,
            'employeeId'              => $this->user->employee_id ?? null,
            'employeeName'            => $this->user->profile->name ?? null,
            'employeeRole'            => $this->user->profile->role->name ?? null,
            'employeeDepartment'      => $this->user->profile->department->name ?? null,
            'date'                    => $this->date,
            'checkInTime'             => $this->check_in_time,
            'checkOutTime'            => $this->check_out_time,
            'checkInPhoto'            => $this->check_in_photo,
            'checkOutPhoto'           => $this->check_out_photo,
            'checkInLatitude'         => $this->check_in_latitude,
            'checkInLongitude'        => $this->check_in_longitude,
            'checkOutLatitude'        => $this->check_out_latitude,
            'checkOutLongitude'       => $this->check_out_longitude,
            'scheduleId'              => $this->schedule_id,
            'checkInStatus'           => $this->check_in_status,
            'checkOutStatus'          => $this->check_out_status,
            'checkInOutsideLocation'  => (bool) $this->check_in_outside_location,
            'checkOutOutsideLocation' => (bool) $this->check_out_outside_location,
            'checkInAddress'          => $this->check_in_address,
            'checkOutAddress'         => $this->check_out_address,
            'checkInComment'          => $this->check_in_comment,
            'checkOutComment'         => $this->check_out_comment,
            'autoCheckout'            => $this->auto_checkout,
        ];
    }
}
