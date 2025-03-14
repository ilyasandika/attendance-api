<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShiftCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($shift) {
                return [
                    'shiftId' => $shift->id,
                    'shiftName' => $shift->name,
                    'description' => $shift->description,
                    'monday' => $this->formatDay($shift->shiftDay, "monday"),
                    'tuesday' => $this->formatDay($shift->shiftDay, "tuesday"),
                    'wednesday' => $this->formatDay($shift->shiftDay, "wednesday"),
                    'thursday' => $this->formatDay($shift->shiftDay, "thursday"),
                    'friday' => $this->formatDay($shift->shiftDay, "friday"),
                    'saturday' => $this->formatDay($shift->shiftDay, "saturday"),
                    'sunday' => $this->formatDay($shift->shiftDay, "sunday"),
                ];
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'total' => $this->total(),
            ],
        ];
    }

    private function formatDay($collection, $day)
    {

        foreach ($collection as $col) {
            if ($col["name"] == $day) {
                return [
                    'in' => $col['check_in'] ?? 0,
                    'out' => $col['check_out'] ?? 0,
                    'breakStart' => $col['break_start'] ?? 0,
                    'breakEnd' => $col['break_end'] ?? 0,
                    'isOff' => $col['is_off'] ?? true,
                ];
            }
        }
    }
}
