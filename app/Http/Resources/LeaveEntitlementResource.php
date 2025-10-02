<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LeaveEntitlementResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>  $this->id,
            'user' => [
                'employeeId' =>  $this->user->employee_id,
                'name' =>  $this->user->profile->name,
                'role' =>  $this->user->profile->role->name,
                'department' =>  $this->user->profile->department->name,
            ],
            'year' => $this->year,
            'totalDays' => $this->total_days,
            'usedDays' => $this->leaves->count(),
            'carriedOver' => $this->carried_year,
        ];
    }
}
