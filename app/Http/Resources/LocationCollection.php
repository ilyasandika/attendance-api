<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" => $this->collection->map(function ($schedule) {
                return [
                    "schedulesId" => $schedule->id,
                    "employeeId" => $schedule->user->employee_id,
                    "employeeName" => $schedule->user->profile->name,
                    "employeeRole" => $schedule->user->profile->role->name,
                    "employeeDepartment" => $schedule->user->profile->department->name,
                    "employeeShift" => $schedule->shift->name,
                    "employeeWorkLocation" => $schedule->location->name
                ];
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
