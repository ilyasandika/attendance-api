<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'shiftId' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'default' => $this->default,
            'allowOutsideLocation' => $this->allow_outside_location,
            "isUsed" => $this->schedule()->exists(),
            'monday' => $this->formatDay("monday"),
            'tuesday' => $this->formatDay("tuesday"),
            'wednesday' => $this->formatDay("wednesday"),
            'thursday' => $this->formatDay("thursday"),
            'friday' => $this->formatDay("friday"),
            'saturday' => $this->formatDay("saturday"),
            'sunday' => $this->formatDay("sunday"),
        ];
    }

    private function formatDay($day)
    {
        $dayData = $this->shiftDay->firstWhere('name', $day);

        return [
            'in' => $dayData->check_in ?? 0,
            'out' => $dayData->check_out ?? 0,
            'breakStart' => $dayData->break_start ?? 0,
            'breakEnd' => $dayData->break_end ?? 0,
            'isOn' => $dayData->is_on ?? true,
        ];
    }
}
