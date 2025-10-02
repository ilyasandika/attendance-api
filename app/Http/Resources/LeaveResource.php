<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */




    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'employeeId' => $this->user->employee_id,
                'name' =>  $this->user->profile->name,
                'role' =>  $this->user->profile->role->name,
                'department' =>  $this->user->profile->department->name,
            ],

            'type' =>  $this->type,
            'startDate' =>  $this->start_date,
            'endDate' =>  $this->end_date,
            'totalDays' =>  $this->total_days,
            'reason' =>  $this->reason,
            'attachment' =>  $this->attachment,
            'attachmentUrl' =>  $this->attachment_url,
            'status' =>  $this->status,
            'approver' => [
                'employeeId' => $this->approver?->employee_id,
                'name' =>  $this->approver?->profile->name,
                'role' =>  $this->approver?->profile->role->name,
                'department' =>  $this->approver?->profile->department->name,
            ],
            'approvedAt' =>  $this->approved_at,
        ];
    }
}
